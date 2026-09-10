<?php

namespace App\Console\Commands;

use App\Hotel;
use App\HotelSatisfaction;
use App\User;
use App\UserBrand;
use App\Visit;
use App\Device;
use App\Connection;
use App\Satisfaction;
use App\Services\BrandService;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;
use App\UserSurvey;
use App\UserSurveyQuestionAnswer;
use App\Survey;
use App\SurveyQuestion;
use App\QuestionBrand;

class GenerateVisitsWithPmsReservation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hotelinking:generateVisits {brandId} {numberUsers=100} {--withPMSData=true} {--withSurveys=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates fake visits with or without PMS reservations and custom survey responses';

    protected $brandService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $brandId = $this->argument('brandId');
        $numberUsers = intval($this->argument('numberUsers'));
        $withPMSData = filter_var($this->option('withPMSData'), FILTER_VALIDATE_BOOLEAN);
        $withSurveys = filter_var($this->option('withSurveys'), FILTER_VALIDATE_BOOLEAN);

        try {
            $brand = $this->brandService->getBrand($brandId);
            $hotel = Hotel::where('id', $brand->hotel_id)->first();
        } catch (\Exception $e) {
            Log::error("Brand not exists!", ["brandId" => $brandId, "exception" => $e]);
            $this->getOutput()->error("No brand id or number users defined!");
            return 0;
        }

        $threshold = HotelSatisfaction::where('id_hotel', $hotel->id)->value('puntMin') ?? 7;

        if ($this->confirm("Do you want to generate $numberUsers fake visits with PMS reservations for brand $brandId?")) {
            $faker = Faker::create();
            $this->getOutput()->progressStart($numberUsers);

            $survey = factory(Survey::class)->create(['brand_id' => $brand->id]);
            $genericQuestion = QuestionBrand::whereNull('brand_id')->first();
            $genericSurveyQuestion = factory(SurveyQuestion::class)->create([
                'survey_id' => $survey->id,
                'question_id' => $genericQuestion->question_id,
                'active' => 1,
                'required' => 1
            ]);

            foreach (range(1, $numberUsers) as $index) {
                $countries = ['Argentina', 'Spain', 'Italy', 'France', 'United States', 'United Kingdom'];
                
                $email = $faker->safeEmail;
                while (User::where('email', $email)->exists()) {
                    $email = $faker->safeEmail;
                }
 
                $user = factory(User::class)->create([
                    'pais'  => $faker->randomElement($countries),
                    'email' => $email,
                ]);
        
                $userLang = $this->getLanguage($user->pais);
                $userBrand = factory(UserBrand::class)->create([
                    'brand_id'  => $brand->id,
                    'user_id'   => $user->id,
                    'date'      => Carbon::today()->subDays(rand(0, 365)),
                    'unsubscribed'  => 1
                ]);

                // Decide visit state based on the withPMSData option
                $visit = $withPMSData
                ? factory(Visit::class)->states('withReservation')->create([
                    'user_brand_id' => $userBrand->id,
                    'brand_id' => $userBrand->brand_id,
                    'user_id' => $userBrand->user_id,
                ])
                : factory(Visit::class)->create([
                    'user_brand_id' => $userBrand->id,
                    'brand_id' => $userBrand->brand_id,
                    'user_id' => $userBrand->user_id,
                ]);
                $device = factory(Device::class)->create();
                factory(Connection::class)->create([
                    'brand_id'  => $brand->id,
                    'visit_id'  => $visit->id,
                    'device_id' => $device->id
                ]);
                // Generate custom survey responses
                $chainId = $brand->chain_id;
                $score = $faker->numberBetween(5, 10);
                $reviewSent = $score >= $threshold ? 1 : 0;
                $comments = $this->getComments();
                $comment = $faker->randomElement($comments[$userLang][$this->getSatisfactionKey($score)]);
                $satisfaction = factory(Satisfaction::class)->create([
                    'id_hotel' => $hotel->id,
                    'id_usuario' => $user->id,
                    'id_cadena' => $chainId ?? 0,
                    'puntuacion' => $score,
                    'comentario' => $comment,
                    'fecha_creado' => now(),
                    'fecha_update' => now(),
                    'done' => 1,
                    'review_send' => $reviewSent,
                    'id_room' =>  $faker->numberBetween($min = 100, $max = 500),
                ]);

                // **New system**: Create UserSurvey y UserSurveyQuestionAnswer if `--withSurveys` is set
                if ($withSurveys) {
                    $assistedAt = $satisfaction->has_been_seen ? now()->subDays(rand(1, 30)) : null;
    
                    $userSurvey = factory(UserSurvey::class)->create([
                        'survey_id' => $survey->id,
                        'brand_id' => $brand->id,
                        'user_id' => $user->id,
                        'user_satisfaction_id' => $satisfaction->id,
                        'assisted' => $satisfaction->has_been_seen,
                        'assisted_at' => $assistedAt,
                        'created_at' => $visit->check_in ? Carbon::parse($visit->check_in)->addDays(rand(1, 3)) : now()
                    ]);
    
                    // Generar respuestas a preguntas genéricas si `Satisfaction` está completada
                    if ($satisfaction->done) {
                        factory(UserSurveyQuestionAnswer::class)->create([
                            'survey_question_id' =>  $genericSurveyQuestion->id,
                            'user_survey_id' => $userSurvey->id,
                            'answer' => $satisfaction->puntuacion,
                            'comment' => $satisfaction->comentario
                        ]);
                    }
                }
    
                $this->getOutput()->progressAdvance();
            }
    
            $this->getOutput()->progressFinish();
        }
    }

    private function getSatisfactionKey($score)
    {
        if ($score >= 9 && $score <= 10) {
            return 'excellent';
        } elseif ($score >= 7 && $score < 9) {
            return 'good';
        }
        return 'average';
    }

    private function getLanguage($country)
    {
        $countryLanguageMap = [
            'Argentina' => 'Spanish',
            'Spain' => 'Spanish',
            'Italy' => 'Italian',
            'France' => 'French',
            'United States' => 'English',
            'United Kingdom' => 'English',
        ];
        return $countryLanguageMap[$country] ?? 'English';
    }

    private function getComments()
    {
        return [
            'English' => [
                'excellent' => [
                    "The hotel staff went above and beyond to make our stay perfect. We will definitely come back!",
                    "From check-in to check-out, everything was seamless and professional. Highly recommended!",
                    "Loved the extra touches like complimentary snacks and the friendly concierge service.",
                    "The room was immaculate, with stunning views of the city. The breakfast buffet was a highlight.",
                    "Exceptional experience! The spa treatments were just what I needed after a long week.",
                    "Truly a gem! Every staff member made us feel like VIPs. We'll return for sure!",
                    "Amazing atmosphere, perfect for a relaxing getaway. The attention to detail was superb.",
                    "The level of service exceeded our expectations. The staff was attentive without being intrusive.",
                    "Unforgettable experience. The rooftop bar had incredible views, and the cocktails were divine.",
                    "The hotel made our anniversary special with a surprise gift in our room. Thank you!",
                    "Absolutely perfect stay. The location was ideal, and the bed was incredibly comfortable.",
                    "Exceptional hospitality and beautiful decor. Every corner of the hotel was thoughtfully designed.",
                    "Can't stop raving about our stay here. The staff's dedication to guest comfort is unmatched.",
                    "Everything was top-notch, from the room service to the personalized welcome notes.",
                    "A truly exceptional stay with outstanding service. The attention to detail was superb.",
                    "One of the best hotel experiences I've ever had. Can't wait to return!",
                    "The staff made us feel special from the moment we arrived. Superb hospitality.",
                    "The decor was stunning and the vibe of the hotel was truly relaxing.",
                    "Our stay was flawless. The concierge made perfect recommendations for local activities.",
                    "Incredible experience! The staff went out of their way to accommodate our needs.",
                    "The view from our room was breathtaking. It was worth every penny.",
                    "Fantastic service and delicious dining options. The chef's specials were a highlight.",
                    "Loved the little touches like the welcome drink and handwritten note in the room.",
                    "We were treated like royalty. The personalized service was beyond expectations."
                ],
                'good' => [
                    "Overall a good experience, but the room was a bit smaller than expected.",
                    "We had a pleasant stay, though the check-in process was a bit slow.",
                    "Comfortable beds and clean facilities, but the Wi-Fi could be faster.",
                    "Enjoyed the location, but the noise from the street was sometimes distracting.",
                    "The breakfast selection was decent, but could use more variety.",
                    "Good service overall, though the housekeeping could be more consistent.",
                    "The hotel is charming, but some parts are starting to show their age.",
                    "Nice experience, but we had issues with the air conditioning in our room.",
                    "Great value for the price, though some amenities were not available.",
                    "The staff was polite, but there were delays with room service.",
                    "The hotel was nice, but a few small issues kept it from being perfect.",
                    "Overall a positive stay, but the check-out process could be smoother.",
                    "The room was comfortable, but the bathroom needed some maintenance.",
                    "Great location, though a bit noisy at night due to street traffic.",
                    "The pool area was lovely, but it was crowded most of the time.",
                    "Decent hotel with friendly staff, but the breakfast options were limited.",
                    "Enjoyed the stay, but had to wait a while for room service.",
                    "Good experience overall, but the fitness center could use more equipment.",
                    "The hotel had charm, but the rooms could use a bit of updating.",
                    "Comfortable stay, but we had issues with the key card multiple times."
                ],
                'average' => [
                    "The room was clean, but the decor felt outdated and could use some improvement.",
                    "Service was okay, but the staff seemed overwhelmed during peak times.",
                    "Decent stay, but there were issues with noise from neighboring rooms.",
                    "The amenities were basic, and some were not in working condition.",
                    "An average experience overall; nothing stood out, but nothing was particularly bad either.",
                    "The room was functional but lacked personality and warmth.",
                    "The food at the hotel restaurant was fine, though nothing exceptional.",
                    "We encountered some delays during check-in and check-out.",
                    "The cleanliness was acceptable, but there were some minor maintenance issues.",
                    "The location is convenient, but the surroundings are not very appealing."
                ]
            ],
            'Spanish' => [
                'excellent' => [
                    "¡La estancia fue espectacular! El personal nos hizo sentir como en casa.",
                    "Todo fue excelente, desde la llegada hasta la salida. Definitivamente, volveremos.",
                    "Nos encantó la atención al detalle, como las flores frescas en la habitación.",
                    "Una experiencia increíble, el desayuno era delicioso y muy variado.",
                    "El spa fue una de las mejores partes de nuestra estancia. ¡Muy relajante!",
                    "Gracias por hacer que nuestro aniversario sea inolvidable. Un servicio de primera.",
                    "La vista desde la habitación era impresionante y la cama, súper cómoda.",
                    "El personal fue extremadamente atento, siempre dispuestos a ayudar con una sonrisa.",
                    "La decoración y el ambiente del hotel fueron simplemente perfectos.",
                    "El restaurante del hotel fue una grata sorpresa, ¡la comida estaba deliciosa!",
                    "Todo fue de primer nivel, sin duda volveremos el próximo año.",
                    "La ubicación era perfecta para explorar la ciudad, ¡sin duda volveremos!",
                    "Una experiencia verdaderamente excepcional, el servicio fue sobresaliente.",
                    "Uno de los mejores hoteles en los que me he hospedado. ¡Espero volver pronto!",
                    "El personal nos hizo sentir especiales desde el primer momento. Hospitalidad excelente.",
                    "La decoración era impresionante y el ambiente del hotel muy relajante.",
                    "Nuestra estancia fue perfecta. El conserje nos dio recomendaciones increíbles.",
                    "¡Increíble experiencia! El personal hizo todo lo posible por atender nuestras necesidades.",
                    "La vista desde nuestra habitación era espectacular, valió cada centavo.",
                    "Servicio fantástico y opciones de comida deliciosas. Los especiales del chef fueron lo mejor.",
                    "Me encantaron los pequeños detalles como el cóctel de bienvenida y la nota escrita a mano.",
                    "Nos trataron como reyes. El servicio personalizado superó todas nuestras expectativas."
                ],
                'good' => [
                    "Una experiencia agradable en general, pero la habitación era un poco pequeña.",
                    "El desayuno fue aceptable, pero podría mejorar en variedad.",
                    "El personal fue amable, aunque el servicio de habitaciones tardó un poco.",
                    "Buen lugar para quedarse, aunque el ruido exterior puede ser un problema.",
                    "La ubicación era excelente, pero la conexión a Internet era lenta.",
                    "Estancia cómoda, aunque la limpieza podría ser más constante.",
                    "Disfrutamos de la estancia, aunque el aire acondicionado no funcionaba bien.",
                    "El hotel era bonito, pero algunos muebles estaban un poco desgastados.",
                    "Buena relación calidad-precio, pero faltaron algunos servicios esperados.",
                    "La piscina era agradable, aunque podría estar mejor mantenida.",
                    "El hotel estuvo bien, pero algunos pequeños problemas impidieron que fuera perfecto.",
                    "En general, una estadía positiva, pero el proceso de check-out podría ser más ágil.",
                    "La habitación era cómoda, pero el baño necesitaba algo de mantenimiento.",
                    "Excelente ubicación, aunque un poco ruidosa por el tráfico nocturno.",
                    "La zona de la piscina era agradable, pero estaba bastante concurrida.",
                    "Hotel decente con personal amable, pero las opciones de desayuno eran limitadas.",
                    "Disfruté la estancia, aunque tuvimos que esperar un rato para el servicio de habitaciones.",
                    "Buena experiencia en general, pero el gimnasio podría tener más equipo.",
                    "El hotel tiene encanto, pero las habitaciones podrían renovarse un poco.",
                    "Estancia cómoda, pero tuvimos problemas con la tarjeta de la habitación varias veces."
                ],
                'average' => [
                    "La habitación estaba limpia, pero los muebles eran antiguos y desgastados.",
                    "El servicio fue regular; el personal parecía sobrecargado en horas punta.",
                    "La experiencia fue decente, pero hubo ruido proveniente de otras habitaciones.",
                    "Las amenidades eran básicas, y algunas no funcionaban correctamente.",
                    "Un promedio de estancia, nada sobresaliente ni particularmente malo.",
                    "La habitación era funcional pero carecía de personalidad y calidez.",
                    "La comida del restaurante estaba bien, aunque nada excepcional.",
                    "Hubo retrasos durante el check-in y el check-out.",
                    "La limpieza fue aceptable, pero hubo algunos problemas menores de mantenimiento.",
                    "La ubicación es conveniente, pero los alrededores no son muy atractivos."
                ]
            ],
            'Italian' => [
                'excellent' => [
                    "Servizio impeccabile! Tutto era perfetto dall'arrivo alla partenza.",
                    "L'hotel ci ha sorpreso con piccoli dettagli che hanno reso il soggiorno indimenticabile.",
                    "La posizione era fantastica, vicino a tutti i principali punti di interesse.",
                    "Il personale era estremamente gentile e attento. Ci torneremo sicuramente.",
                    "Il centro benessere è stato il miglior modo per concludere la nostra vacanza.",
                    "Non possiamo che consigliare questo hotel a chiunque cerchi un'esperienza straordinaria.",
                    "Camere eleganti, servizio eccellente e una vista mozzafiato dalla terrazza.",
                    "Un soggiorno perfetto con un'attenzione continua alle nostre esigenze.",
                    "La colazione era abbondante e di alta qualità, con un'ampia scelta di pietanze.",
                    "Grazie per aver reso la nostra luna di miele speciale, torneremo sicuramente!",
                    "Un soggiorno davvero eccezionale con un servizio straordinario. Ogni dettaglio era curato.",
                    "Una delle migliori esperienze in hotel che abbia mai avuto. Non vedo l'ora di tornare!",
                    "Il personale ci ha fatto sentire speciali fin dal nostro arrivo. Ospitalità impeccabile.",
                    "La decorazione era stupenda e l'atmosfera dell'hotel molto rilassante.",
                    "Il nostro soggiorno è stato impeccabile. Il concierge ci ha dato ottimi consigli.",
                    "Esperienza incredibile! Il personale ha fatto di tutto per soddisfare le nostre esigenze.",
                    "La vista dalla nostra camera era mozzafiato, ne è valsa la pena.",
                    "Servizio fantastico e opzioni culinarie deliziose. Gli speciali dello chef erano fantastici.",
                    "Adorato i piccoli dettagli come il drink di benvenuto e il biglietto scritto a mano.",
                    "Siamo stati trattati come dei re. Il servizio personalizzato ha superato ogni aspettativa."
                ],
                'good' => [
                    "Esperienza buona, ma la stanza era un po' rumorosa.",
                    "Personale gentile, ma il servizio di pulizia non era sempre puntuale.",
                    "La colazione era discreta, ma potrebbe essere più varia.",
                    "Ottima posizione, ma il Wi-Fi era lento.",
                    "Hotel accogliente, ma alcuni mobili erano un po' datati.",
                    "Buon rapporto qualità-prezzo, anche se mancano alcuni servizi.",
                    "Stanza confortevole, ma abbiamo avuto problemi con l'aria condizionata.",
                    "Bella atmosfera, ma c'erano ritardi nel servizio.",
                    "Struttura carina, ma alcune aree necessitano di ristrutturazione.",
                    "Il ristorante era buono, ma il servizio era lento.",
                    "L'hotel era carino, ma alcuni piccoli problemi gli hanno impedito di essere perfetto.",
                    "In generale un soggiorno positivo, ma il check-out potrebbe essere più veloce.",
                    "La camera era confortevole, ma il bagno necessitava di qualche riparazione.",
                    "Ottima posizione, anche se un po' rumorosa di notte a causa del traffico.",
                    "La zona piscina era bella, ma spesso affollata.",
                    "Hotel decente con personale gentile, ma poche opzioni per la colazione.",
                    "Soggiorno piacevole, ma il servizio in camera era un po' lento.",
                    "Buona esperienza nel complesso, ma la palestra potrebbe avere più attrezzature.",
                    "L'hotel ha fascino, ma le camere potrebbero essere rinnovate.",
                    "Soggiorno confortevole, ma abbiamo avuto problemi con la tessera più volte."
                ],
                'average' => [
                    "La stanza era pulita, ma l'arredamento sembrava datato.",
                    "Il servizio era nella norma, ma il personale sembrava sopraffatto nei momenti di punta.",
                    "Un soggiorno medio, con qualche problema di rumore dalle stanze adiacenti.",
                    "Le attrezzature erano basilari e alcune non funzionavano correttamente.",
                    "Esperienza complessivamente media; nulla di eccezionale né di pessimo.",
                    "La stanza era funzionale ma mancava di personalità.",
                    "Il cibo al ristorante dell'hotel era accettabile, ma niente di speciale.",
                    "Abbiamo avuto ritardi sia al check-in che al check-out.",
                    "La pulizia era sufficiente, ma c'erano alcuni problemi di manutenzione.",
                    "La posizione è comoda, ma l'ambiente circostante non è molto invitante."
                ],
            ],
            'French' => [
                'excellent' => [
                    "Séjour exceptionnel ! Le service était irréprochable, et la chambre sublime.",
                    "Le personnel a fait des efforts incroyables pour rendre notre séjour parfait.",
                    "L'hôtel était magnifiquement décoré et très bien situé. Un vrai coup de cœur.",
                    "Nous avons adoré le service personnalisé, chaque détail était pensé.",
                    "Le petit déjeuner était un véritable festin, avec des options pour tous les goûts.",
                    "Tout était parfait, nous avons hâte de revenir bientôt.",
                    "Merci pour l'accueil chaleureux, nous nous sommes sentis comme à la maison.",
                    "Le spa était divin, une excellente manière de terminer notre séjour.",
                    "Séjour mémorable avec une attention constante au moindre détail.",
                    "L'expérience client était exceptionnelle, bravo à toute l'équipe.",
                    "Un séjour vraiment exceptionnel avec un service hors pair. Chaque détail était parfait.",
                    "L'une des meilleures expériences hôtelières que j'ai jamais vécues. Vivement le prochain séjour !",
                    "Le personnel nous a fait sentir spéciaux dès notre arrivée. Hospitalité impeccable.",
                    "La décoration était magnifique et l'atmosphère de l'hôtel très apaisante.",
                    "Notre séjour a été sans faute. Le concierge nous a donné des recommandations parfaites.",
                    "Expérience incroyable ! Le personnel a fait tout son possible pour répondre à nos besoins.",
                    "La vue depuis notre chambre était à couper le souffle, cela valait chaque centime.",
                    "Service fantastique et options culinaires délicieuses. Les spécialités du chef étaient un régal.",
                    "J'ai adoré les petites attentions comme le verre de bienvenue et la note manuscrite.",
                    "Nous avons été traités comme des rois. Le service personnalisé était au-delà de nos attentes."
                ],
                'good' => [
                    "Expérience globalement positive, mais la chambre était un peu bruyante.",
                    "Bon hôtel, mais le service de ménage n'était pas toujours au rendez-vous.",
                    "Le petit-déjeuner était correct, mais sans plus.",
                    "Bonne situation géographique, mais quelques nuisances sonores.",
                    "L'hôtel est agréable, mais le Wi-Fi était très lent.",
                    "Le personnel était gentil, mais le service pourrait être amélioré.",
                    "Séjour confortable, mais la climatisation ne fonctionnait pas bien.",
                    "Bel hôtel, mais quelques installations mériteraient une rénovation.",
                    "Rapport qualité-prix correct, mais quelques équipements manquaient.",
                    "Le restaurant était bon, mais le service était un peu lent.",
                    "L'hôtel était bien, mais quelques petits soucis l'ont empêché d'être parfait.",
                    "Séjour globalement positif, mais le départ pourrait être plus fluide.",
                    "La chambre était confortable, mais la salle de bain nécessitait des réparations.",
                    "Super emplacement, mais un peu bruyant la nuit à cause du trafic.",
                    "La piscine était agréable, mais souvent bondée.",
                    "Hôtel correct avec un personnel sympathique, mais peu de choix au petit déjeuner.",
                    "Bon séjour, mais le service en chambre a pris du temps.",
                    "Bonne expérience dans l'ensemble, mais la salle de sport pourrait être mieux équipée.",
                    "L'hôtel a du charme, mais les chambres pourraient être modernisées.",
                    "Séjour confortable, mais des problèmes récurrents avec la carte-clé."
                ],
                'average' => [
                    "La chambre était propre, mais la décoration semblait dépassée.",
                    "Le service était correct, mais le personnel semblait débordé aux heures de pointe.",
                    "Un séjour moyen, avec des problèmes de bruit venant des chambres voisines.",
                    "Les équipements étaient basiques et certains ne fonctionnaient pas.",
                    "Expérience globale moyenne; rien de remarquable ni de catastrophique.",
                ],
            ],
        ];
    }
}

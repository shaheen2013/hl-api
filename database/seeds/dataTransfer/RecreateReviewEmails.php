<?php

use Illuminate\Database\Seeder;
use App\Brand;
use App\User;
use App\Satisfaction;
use App\Email;

class RecreateReviewEmails extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $corruptedReviewHotelsID = [22, 23, 35, 36, 40, 48, 73, 74, 82, 98, 106, 128, 154, 196, 201, 207, 224, 236, 237, 240, 243, 244, 245, 246, 253, 254, 292, 295, 296, 298, 311, 315, 328, 339, 360, 361, 365, 367, 375, 380, 381, 382, 384, 386, 393, 394, 396, 425, 454, 456, 462, 463, 464, 465, 471, 475, 476, 480, 499, 502, 504, 518, 522, 532, 535, 536, 539, 542, 543, 547, 548, 549, 562, 563, 565, 577, 579, 586, 587, 588, 590, 592, 593, 594, 596, 602, 603, 606, 607, 608, 609, 610, 612, 613, 616, 618, 620, 621, 622, 631, 632, 634, 637, 638, 639, 640, 644, 646, 653, 655, 656, 666, 667, 668, 669, 670, 671, 672, 673, 674, 675, 676, 677, 678, 679, 680, 681, 682, 683, 684, 686, 689, 690, 691, 692, 693, 694, 695, 696, 697, 698, 699, 700, 702, 703, 704, 705, 707, 709, 710, 713, 718, 719, 721, 722, 723, 724, 725, 726, 727, 729, 730, 731, 732, 733, 734, 737, 738, 739, 740, 741, 742, 743, 744, 745, 747, 750, 751, 752, 753, 754, 756, 757, 758, 759, 760, 761, 762, 763, 764, 765, 766, 767, 768, 773, 774, 775, 776, 777, 778, 779, 780, 781, 782, 783, 784, 785, 786, 787, 788, 789, 790, 791, 792, 793, 801, 802, 803, 806, 808, 809, 810, 811, 812, 813, 816, 817, 820, 821, 825, 826, 827, 828, 829, 830, 831, 832, 833, 834, 835, 836, 837, 838, 839, 840, 841, 842, 843, 844, 845, 846, 847, 848, 849, 850, 851, 852, 853, 854, 855, 856, 857, 858, 859, 860, 861, 862, 863, 864, 865, 866, 867, 868, 869, 870, 871, 872, 873, 874, 875, 876, 877, 878, 879, 882, 883, 884, 886, 888, 889, 890, 891, 892, 893, 896, 897, 898, 899, 900, 901, 902, 903, 904, 905, 906, 908, 909, 910, 911, 912, 913, 914, 915, 916, 917, 918, 919, 920, 921, 922, 923, 924, 928, 929, 930, 931, 932, 935, 936, 937, 938, 939, 940, 968, 973, 974, 975, 976, 977, 978, 990, 992, 995, 996, 1000, 1001, 1002, 1004, 1011, 1012, 1013, 1018, 1028, 1029, 1030, 1037, 1038, 1042, 1043]; 
        $corruptedReviewBrandID = Brand::whereIn('hotel_id', $corruptedReviewHotelsID)->get()->pluck('id');

        // Delete corrupted emails and interactions
        Email::where([
            ["email_type_id", "=", 1],
            ['created_at', '<', '2020-02-01']
        ])->whereIn('brand_id', $corruptedReviewBrandID)->delete();
        
        $this->command->getOutput()->progressStart(
            Satisfaction::where([
                ["done", '=',  1], 
                ["review_send", '=', 1],
                ["fecha_update", '<', '2020-02-01']
            ])
            ->whereIn('id_hotel', $corruptedReviewHotelsID)
            ->count()
        );

        Satisfaction::where([
            ["done", '=',  1], 
            ["review_send", '=', 1],
            ["fecha_update", '<', '2020-02-01']
        ])
        ->whereIn('id_hotel', $corruptedReviewHotelsID)
        ->chunk(100, function($satisfactions)
        {
            $emails = [];

            foreach ($satisfactions as $satisfaction)
            {
                $user = User::where(['id' => $satisfaction->id_usuario])->first();
                $brand = Brand::where(['hotel_id' => $satisfaction->id_hotel])->first();

                if ($user && $brand) {
                    array_push($emails, [
                        "user_id" => $user->id,
                        "brand_id" => $brand->id,
                        "sender" => data_get($brand, 'hotel.sending_email') ?
                            data_get($brand, 'hotel.sending_email') :
                            data_get($brand, 'hotel.email'),
                        "receiver" => $user->email,
                        "email_type_id" => 1,
                        "created_at" => $satisfaction->fecha_update                      
                    ]);    
                }
                
                $this->command->getOutput()->progressAdvance();
            }

            Email::insert($emails);
        });
        
        $this->command->getOutput()->progressFinish();

    }
}

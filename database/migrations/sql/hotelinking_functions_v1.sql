/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping routines for database 'hotelinking'
--
/*!50003 DROP FUNCTION IF EXISTS `bookedUsersHotel` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;

CREATE FUNCTION `bookedUsersHotel`(p_idusuario int, p_idhotel int) RETURNS varchar(20) CHARSET utf8 COLLATE utf8_spanish_ci
BEGIN
    DECLARE VAL INT(200);
    SELECT COUNT(*)
    INTO VAL
    FROM(SELECT DISTINCT user_checkin.id_usuario AS id1, used_promocode.id_usuario AS id2
         FROM referrer_users
                  LEFT JOIN user_checkin ON user_checkin.id_usuario=referrer_users.invitado AND user_checkin.id_hotel=p_idhotel
                  LEFT JOIN used_promocode ON used_promocode.id_usuario=referrer_users.invitado AND used_promocode.id_hotel=p_idhotel
         WHERE referrer_users.invitador=p_idusuario AND referrer_users.id_hotel=p_idhotel
           AND (user_checkin.id_hotel=p_idhotel OR user_checkin.id_hotel IS NULL )
           AND (user_checkin.id_hotel IS NOT NULL OR used_promocode.id_hotel IS NOT NULL )
        ) AS n;

    RETURN VAL;
END ;

/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `bookedUsersHotel2` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;

CREATE FUNCTION `bookedUsersHotel2`(p_idusuario int, p_idhotel int) RETURNS int(11)
BEGIN
    DECLARE VAL INT(11);
    SELECT COUNT(tracking_cookies.id)
    INTO VAL
    FROM tracking_cookies
    WHERE tracking_cookies.referrer_id=p_idusuario AND tracking_cookies.hotel_id=p_idhotel
      AND (tracking_cookies.transaction_num !=''  OR tracking_cookies.amount !='');
    RETURN VAL;
END ;

/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `bookingValueReferralsUserHotel` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;

CREATE FUNCTION `bookingValueReferralsUserHotel`(p_idusuario int, p_idhotel int) RETURNS float
BEGIN
    DECLARE VAL float;
    SELECT ROUND(IFNULL(SUM(conversion_current_coin), 0), 2)
    INTO VAL
    FROM referrer_users
             INNER JOIN booking_value ON booking_value.id_usuario=referrer_users.invitado
    WHERE referrer_users.invitador=p_idusuario AND booking_value.id_checkout=0 AND booking_value.id_hotel=p_idhotel;
    RETURN VAL;
END ;

/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `bookingValueUserHotel` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;

CREATE FUNCTION `bookingValueUserHotel`(p_idusuario int, p_idhotel int) RETURNS varchar(20) CHARSET utf8 COLLATE utf8_spanish_ci
BEGIN
    DECLARE VAL INT(200);
    SELECT ROUND(IFNULL(SUM(conversion_current_coin), 0), 2)
    INTO VAL
    FROM referrer_users
             INNER JOIN booking_value ON booking_value.id_usuario=referrer_users.invitado
    WHERE referrer_users.invitador=p_idusuario AND booking_value.id_checkout=0 AND booking_value.id_hotel=p_idhotel;
    RETURN VAL;
END ;

/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `condicionesOfertaLang` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;

CREATE FUNCTION `condicionesOfertaLang`( p_id_oferta INT, p_lang VARCHAR(50) ) RETURNS longtext CHARSET utf8 COLLATE utf8_spanish_ci
BEGIN
    DECLARE val  LONGTEXT;
    SET val =
            (SELECT CASE
                        WHEN COUNT(condiciones)=1 THEN condiciones
                        ELSE (SELECT condiciones FROM hotel_oferta_lang WHERE lang='en' AND hotel_oferta_lang.id_oferta=p_id_oferta LIMIT 1)
                        END AS condiciones
             FROM hotel_oferta_lang
             WHERE id_oferta=p_id_oferta AND lang=p_lang LIMIT 1 );
    RETURN val;
END ;

/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `cuponesOfertaUsuario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;

CREATE FUNCTION `cuponesOfertaUsuario`(p_idusuario int, p_idoferta int) RETURNS int(11)
BEGIN
    DECLARE VAL INT(200);
    SELECT COUNT(id)
    INTO VAL
    FROM user_cupones
    WHERE id_usuario=p_idusuario
      AND id_oferta=p_idoferta;
    RETURN VAL;
END ;

/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `cuponStayPrestay` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;

CREATE FUNCTION `cuponStayPrestay`(p_id_cupon int) RETURNS int(11)
BEGIN
    DECLARE VAL int(10);
    SET val =
            ( SELECT
                  CASE WHEN oferta_referral_token.id_tipo_share='2' OR oferta_referral_token.id_tipo_share='3' THEN '1'
                       WHEN used_promocode.id_tipo_share='2' OR used_promocode.id_tipo_share='3' THEN '1'
                       ELSE '0'
                      END AS cuponStayPrestay
              FROM user_cupones
                       LEFT JOIN oferta_referral_token ON oferta_referral_token.id_cupon=user_cupones.id
                       LEFT JOIN used_promocode ON used_promocode.id_cupon=user_cupones.id
              WHERE user_cupones.id=p_id_cupon);
    RETURN val;
END ;

/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `descripcionOfertaLang` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;

CREATE FUNCTION `descripcionOfertaLang`( p_id_oferta INT, p_lang  VARCHAR(50) ) RETURNS longtext CHARSET utf8 COLLATE utf8_spanish_ci
BEGIN
    DECLARE val LONGTEXT;
    SET val =
            (SELECT CASE
                        WHEN COUNT(descripcion)=1 THEN descripcion
                        ELSE (SELECT descripcion FROM hotel_oferta_lang WHERE lang='en' AND hotel_oferta_lang.id_oferta=p_id_oferta  LIMIT 1)
                        END AS descripcion
             FROM hotel_oferta_lang
             WHERE id_oferta=p_id_oferta AND lang=p_lang LIMIT 1 );
    RETURN val;
END ;

/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `followersVisits` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;

CREATE FUNCTION `followersVisits`(p_idusuario int, p_idhotel int) RETURNS int(11)
BEGIN
    DECLARE VAL INT(11);
    SELECT COUNT(DISTINCT referrer_tokens_ips.id_token, referrer_tokens_ips.ip)
    INTO VAL
    FROM referrer_tokens_ips
             INNER JOIN referrer_tokens ON referrer_tokens.id=referrer_tokens_ips.id_token
             INNER JOIN users ON users.id=referrer_tokens.id_usuario
             INNER JOIN user_encuestas ON user_encuestas.id=referrer_tokens.id_encuesta
    WHERE referrer_tokens.id_usuario=p_idusuario AND user_encuestas.id_hotel=p_idhotel ;
    RETURN VAL;
END ;

/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `followersVisits2` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;

CREATE FUNCTION `followersVisits2`(p_idusuario int, p_idhotel int) RETURNS int(11)
BEGIN
    DECLARE VAL INT(11);
    SELECT COUNT(tracking_cookies.id)
    INTO VAL
    FROM tracking_cookies
    WHERE tracking_cookies.referrer_id=p_idusuario AND tracking_cookies.hotel_id=p_idhotel ;
    RETURN VAL;
END ;

/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `nameOfertaLang` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;

CREATE FUNCTION `nameOfertaLang`( p_id_oferta INT, p_lang VARCHAR(50) ) RETURNS varchar(100) CHARSET utf8 COLLATE utf8_spanish_ci
BEGIN
    DECLARE val  VARCHAR(100);
    SET val =
            (SELECT CASE
                        WHEN COUNT(nombre)!=0 AND nombre IS NOT NULL THEN nombre
                        ELSE (SELECT nombre FROM hotel_oferta_lang WHERE lang='en' AND hotel_oferta_lang.id_oferta=p_id_oferta LIMIT 1)
                        END AS nombre
             FROM hotel_oferta_lang
             WHERE id_oferta=p_id_oferta AND lang=p_lang LIMIT 1);
    RETURN val;
END ;

/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `pendingToBook` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;

CREATE FUNCTION `pendingToBook`(p_idusuario int, p_idhotel int) RETURNS int(11)
BEGIN
    DECLARE VAL INT(200);
    SELECT COUNT(invitado)
    INTO VAL
    FROM referrer_users
             LEFT JOIN used_promocode ON used_promocode.id_usuario=referrer_users.invitado
        AND used_promocode.id_hotel ='12'
    WHERE invitador=p_idusuario AND referrer_users.id_hotel=p_idhotel
      AND invitado NOT IN (SELECT DISTINCT id_usuario FROM user_checkin WHERE id_hotel=p_idhotel)
      AND invitado NOT IN (SELECT DISTINCT id_usuario FROM used_promocode WHERE id_hotel=p_idhotel);
    RETURN VAL;
END ;

/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `obtenercanjeador` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;

CREATE FUNCTION `obtenercanjeador`(p_canjeado int, p_id_canjeador int, p_tipo_canjeador varchar(20) ) RETURNS varchar(20) CHARSET utf8
BEGIN
    DECLARE VAL varchar(20);
    SET val =
            ( SELECT
                  CASE WHEN p_canjeado = '0' THEN ''
                       WHEN p_tipo_canjeador='staff' AND COUNT(hotel_staff.id) > 0 THEN hotel_staff.nombre
                       WHEN p_tipo_canjeador='staff' THEN hotel_staff_inactivo.nombre
                       WHEN p_tipo_canjeador='hotel' THEN hoteles.hotelName
                       WHEN p_tipo_canjeador='cadena' THEN cadena.nombre
                       ELSE ''
                      END AS canjeador
              FROM hoteles
                       LEFT JOIN cadena ON cadena.id=p_id_canjeador
                       LEFT JOIN hotel_staff ON hotel_staff.id=p_id_canjeador
                       LEFT JOIN hotel_staff_inactivo ON hotel_staff_inactivo.id_staff=p_id_canjeador
              WHERE hoteles.id=p_id_canjeador );
    RETURN val;
END ;

/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `pendingToBook2` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;

CREATE FUNCTION `pendingToBook2`(p_idusuario int, p_idhotel int) RETURNS int(11)
BEGIN
    DECLARE VAL INT(11);
    SELECT COUNT(tracking_cookies.id)
    INTO VAL
    FROM tracking_cookies
    WHERE tracking_cookies.referrer_id=p_idusuario AND tracking_cookies.hotel_id=p_idhotel
      AND (tracking_cookies.transaction_num='' AND tracking_cookies.amount='');
    RETURN VAL;
END ;

/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `redeemedOffers` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;

CREATE FUNCTION `redeemedOffers`(p_idusuario int, p_idhotel int, p_idcadena int, p_chkin_date date) RETURNS int(11)
BEGIN
    DECLARE VAL INT(200);
    SELECT COUNT(user_cupones.id)
    INTO VAL
    FROM user_cupones
             INNER JOIN hotel_oferta ON hotel_oferta.id=user_cupones.id_oferta
    WHERE id_usuario=p_idusuario
      AND (hotel_oferta.id_hotel=p_idhotel OR  hotel_oferta.id_cadena=p_idcadena)
      AND fecha_canj BETWEEN p_chkin_date AND NOW() AND canjeado=1;
    RETURN VAL;
END ;

/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `referralsInDB` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;

CREATE FUNCTION `referralsInDB`(p_idusuario int, p_idhotel int) RETURNS varchar(20) CHARSET utf8 COLLATE utf8_spanish_ci
BEGIN
    DECLARE VAL INT(200);
    SELECT COUNT(invitado)
    INTO VAL
    FROM referrer_users
             LEFT JOIN user_encuestas ON user_encuestas.id=referrer_users.id_encuesta
    WHERE invitador=p_idusuario AND user_encuestas.id_hotel=p_idhotel AND
            invitado NOT IN (SELECT DISTINCT id_usuario FROM user_checkin WHERE id_hotel=p_idhotel);
    RETURN VAL;
END ;

/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `taglineLang` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;

CREATE FUNCTION `taglineLang`( p_id_hotel INT, p_lang VARCHAR(50) ) RETURNS varchar(250) CHARSET utf8 COLLATE utf8_spanish_ci
BEGIN
    DECLARE val  VARCHAR(250);
    SET val =
            (SELECT CASE
                        WHEN COUNT(tagline)=1 THEN tagline
                        ELSE (SELECT tagline FROM hotel_tagline_lang WHERE lang='en' AND hotel_tagline_lang.id_hotel=p_id_hotel LIMIT 1)
                        END AS tagline
             FROM hotel_tagline_lang
             WHERE id_hotel=p_id_hotel AND lang=p_lang );
    RETURN val;
END ;

/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `totalReferrals` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;

CREATE FUNCTION `totalReferrals`(p_idusuario int, p_idhotel int) RETURNS varchar(20) CHARSET utf8 COLLATE utf8_spanish_ci
BEGIN
    DECLARE VAL INT(200);
    SELECT COUNT(invitado)
    INTO VAL
    FROM referrer_users
             LEFT JOIN user_encuestas ON user_encuestas.id=referrer_users.id_encuesta
    WHERE invitador=p_idusuario AND user_encuestas.id_hotel=p_idhotel ;
    RETURN VAL;
END ;

/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `totalReferrals2` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;

CREATE FUNCTION `totalReferrals2`(p_idusuario int, p_idhotel int) RETURNS int(11)
BEGIN
    DECLARE VAL INT(11);
    SELECT COUNT(tracking_cookies.id)
    INTO VAL
    FROM tracking_cookies
    WHERE tracking_cookies.referrer_id=p_idusuario AND tracking_cookies.hotel_id=p_idhotel AND referral_id !='';
    RETURN VAL;
END ;

/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `totalSpentReferralsUserHotel` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;

CREATE FUNCTION `totalSpentReferralsUserHotel`(p_idusuario int, p_idhotel int) RETURNS float
BEGIN
    DECLARE VAL float;
    SELECT
        ROUND (IFNULL((SELECT SUM(IFNULL(conversion_current_coin, 0))
                       FROM referrer_users
                                LEFT JOIN booking_value ON booking_value.id_usuario=referrer_users.invitado AND booking_value.id_hotel=p_idhotel
                       WHERE referrer_users.invitador=p_idusuario AND referrer_users.id_hotel=p_idhotel AND booking_value.id_checkout=0),0)
                   +
               IFNULL((SELECT SUM(IFNULL(money, 0))
                       FROM referrer_users
                                LEFT JOIN user_money_hotel ON user_money_hotel.id_usuario=referrer_users.invitado AND user_money_hotel.id_hotel=p_idhotel
                       WHERE referrer_users.invitador=p_idusuario AND referrer_users.id_hotel=p_idhotel),0),2)
    INTO VAL;
    RETURN VAL;
END ;

/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `totalSpentReferralsUserHotel2` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;

CREATE FUNCTION `totalSpentReferralsUserHotel2`(p_idusuario int, p_idhotel int) RETURNS float
BEGIN
    DECLARE VAL float;
    SELECT SUM(amount)
    INTO VAL
    FROM tracking_cookies
    WHERE tracking_cookies.referrer_id=p_idusuario AND tracking_cookies.hotel_id=p_idhotel;
    RETURN VAL;
END ;

/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `totalSpentUserHotel` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;

CREATE FUNCTION `totalSpentUserHotel`(p_idusuario int, p_idhotel int) RETURNS float
BEGIN
    DECLARE VAL float;
    SELECT
            IFNULL((SELECT ROUND(SUM(IFNULL(conversion_current_coin, 0)),2)
                    FROM booking_value
                    WHERE id_usuario=p_idusuario AND id_hotel=p_idhotel AND booking_value.id_checkout=0),0)
            +
            IFNULL((SELECT ROUND(SUM(IFNULL(money, 0)),2)
                    FROM user_money_hotel
                    WHERE id_usuario=p_idusuario AND id_hotel=p_idhotel),0)
    INTO VAL;
    RETURN VAL;
END ;

/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `totalSpentUserHotel2` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;

CREATE FUNCTION `totalSpentUserHotel2`(p_idusuario int, p_idhotel int) RETURNS float
BEGIN
    DECLARE VAL float;
    SELECT
            IFNULL((SELECT ROUND(SUM(IFNULL(amount, 0)),2)
                    FROM tracking_cookies
                    WHERE tracking_cookies.referral_id=p_idusuario AND tracking_cookies.hotel_id=p_idhotel ),0)
            +
            IFNULL((SELECT ROUND(SUM(IFNULL(money, 0)),2)
                    FROM user_money_hotel
                    WHERE id_usuario=p_idusuario AND id_hotel=p_idhotel),0)
    INTO VAL;
    RETURN VAL;
END ;

/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `visitedHotel` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;

CREATE FUNCTION `visitedHotel`(p_idusuario int, p_idhotel int) RETURNS varchar(20) CHARSET utf8 COLLATE utf8_spanish_ci
BEGIN
    DECLARE VAL INT(200);
    SELECT COUNT(DISTINCT(user_checkin.id_usuario))
    INTO VAL
    FROM referrer_users
             LEFT JOIN user_checkin ON user_checkin.id_usuario=referrer_users.invitado
    WHERE referrer_users.invitador=p_idusuario
      AND user_checkin.id_hotel=p_idhotel;
    RETURN VAL;
END ;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

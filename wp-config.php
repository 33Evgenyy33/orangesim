<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define('DB_NAME', 'orangerucom');

/** Имя пользователя MySQL */
define('DB_USER', 'root');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', '');

/** Имя сервера MySQL */
define('DB_HOST', 'localhost');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8mb4');

/** Схема сопоставления. Не меняйте, если не уверены. */
define('DB_COLLATE', '');

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '^D&sKt_n03O:>9^5ZWYE*77L?ZvZ}l_Pp]IxjNrGlos.bq@$c/CZjN5 45u~f[cF');
define('SECURE_AUTH_KEY',  'An>6-nS#S_~Yd^7m6|8D7[#w`f@T_=|Ixuqt2o55%)/rnu,>L.`,Ko 2J/`QJeyk');
define('LOGGED_IN_KEY',    '??6 oEW1X1=KHDk6Qc!sKU`-b55}*!$Id^~Dm2/T%#W5qb{(a<1ko7t^|+3g^uHm');
define('NONCE_KEY',        'Z2T( k2S/vzX%Y,Eg|$,]+,1N@zKR(.:W-H/46>am3DZ0~z(UJ.86<(s/j2z.bC{');
define('AUTH_SALT',        '@CuUe37b]P^{foa:: yi:ukZtMgo4kTu4ossUx.8r;>HyL*v)Dda(cK$FeWJA+iZ');
define('SECURE_AUTH_SALT', '+_i!`1zE%,Z/#^MpGy/CyT{k+iD3s48!kB(zH6[Q{DF1[)f6>.U|axc5o99F%H?H');
define('LOGGED_IN_SALT',   'BcCUS2EOVFZ[NA|87,9Oe~v5 r(EQ0Jks6` ]EO6#wz`EpDxSoPm3=XV6iQMx_s/');
define('NONCE_SALT',       '<:[Ekwp(Q<qQ.>.9qD9mgG-G,8uoi?X,Mnl}H:-H:ePoMY`N4;,h54g<7rA}o1:K');

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix  = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Инициализирует переменные WordPress и подключает файлы. */
require_once(ABSPATH . 'wp-settings.php');

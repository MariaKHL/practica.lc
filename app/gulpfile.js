/**
* @author Khlystun Maria <hlystun.maria@gmail.com>
* @package gulpfile.js
* gulp config file.
*
* @version 1.0.0
* @link https://github.com/MariaKHL/practica.lc.git
* @copyright 2021 Khlystun Maria
* @license MIT LICENSE Copyright (c) 2021 Khlystun Maria.
* @NOTE: INNER DOC LANGUAGE IS RU;
*/

// Подключение gulp и его модулей.
const { src, dest, parallel, series, watch } = require('gulp');

// Адрес для прокси, на котором ведется разработка.
const proxyAddress 		= 'practica.lc';

// Системный разделитель.
const separator 			= '/';

// Имя директории с ресурсами.
const sourceName 			= 'src' + separator;

// Путь до ресурсов приложения.
const applicationSource = sourceName + separator;

// Для выбора всех файлов в директории.
const allFiles					= '**/*';

// Запуск browsersync и плагинов
const path 					= require('path');
const browserSync 	= require('browser-sync').create();
const concat 				= require('gulp-concat');
const uglify 				= require('gulp-uglify-es').default;
const sass					= require('gulp-sass');
sass.compiler 			= require('node-sass');
const autoprefixer	= require('gulp-autoprefixer');
const clean_css			= require('gulp-clean-css');
const imagemin			= require('gulp-imagemin');
const newer					= require('gulp-newer');
const del						= require('del');


// Конфигурация работы browsersync
function browsersync() {
	browserSync.init({
		proxy 	: proxyAddress,
		// Уведомления
		notify 	: false,
		// Публичный доступ к проекту по локальной сети Wi-Fi/LAN
		online 	: true,
  });
}

// Оптимизация изображений
function images() {
	return src( sourceName + 'img' + separator + allFiles )
	.pipe(newer('dist/i'))
	.pipe(imagemin())
	.pipe(dest('dist/i'))
	.pipe(browserSync.stream());
}

// Очистка директории изображений
function cleani() {
	return del('dist/i/**/*', { force : true });
}

// Работа со скриптами JS (сборка, оптимизация, минификация)
function scripts() {
	return src([
    sourceName + 'js/lib/swiper.min.js',
    sourceName + 'js/lib/jquery.min.js',
    sourceName + 'js/modules/**/*.js',
		sourceName + 'js/app.js'
	])
	.pipe(concat('bundle.min.js'))
	.pipe(uglify())
	.pipe(dest('dist'))
	.pipe(browserSync.stream());
}

// Работа со стилями SCSS, SASS
function styles() {
	return src([
		'src/scss/main.scss'
	])
	.pipe(sass())
	.pipe(concat('bundle.min.css'))
	.pipe(autoprefixer({ overrideBrowserslist : ['last 10 versions'], grid : true }))
	.pipe(clean_css(( { level : { 1: { specialComments: 0 } }/* , format : 'beautify' */ } )))
	.pipe(dest('dist'))
	.pipe(browserSync.stream());
}

// Конфигурация надзора за изменениями.
function initWatch() {
	watch( [ 'src/js/**/*.js', '!**/bundle.min.js'  ], 	scripts );
	watch( [ 'src/scss/**/*.scss', 	'!**/bundle.min.css' ], 	styles 	);
	watch( [ 'src/img/**/*', 		'!node_modules/**/*' ],		images 	);
	watch( [ 'backend/**/*.html', 	'!**/bundle.min.html'] ).on('change', browserSync.reload);
	watch( [ 'backend/**/*.php',  'view/**/*.php',		'!**/bundle.min.php' ] ).on('change', browserSync.reload);
}

// Экспорт таска browsersync.
exports.browsersync = browsersync;

// Экспорт таска scripts.
exports.scripts 		= scripts;

// Экспорт таска styles.
exports.styles 			= styles;

// Экспорт таска images.
exports.images 			= images;

// Экспорт таска cleani.
exports.cleani 			= cleani;


// Запуск параллельного наблюдения из изменением файлов.
exports.default			= parallel(images, styles, scripts, browsersync, initWatch);

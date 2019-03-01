/* ---- THE FOLLOWING CONFIG SHOULD BE EDITED ---- */

const pkg = require( './package.json' );

function parseKeywords( keywords ) {
	// These keywords are useful for Packagist/NPM/Bower, but not for the WordPress plugin repository.
	const disallowed = [ 'wordpress', 'plugin' ];

	return keywords.filter( keyword => ! disallowed.includes( keyword ) );
}

const config = {
	pluginSlug: 'wp-gdpr-cookie-notice',
	pluginName: 'WP GDPR Cookie Notice',
	pluginURI: pkg.homepage,
	author: pkg.author.name,
	authorURI: pkg.author.url,
	description: pkg.description,
	version: pkg.version,
	license: 'GNU General Public License v2 (or later)',
	licenseURI: 'http://www.gnu.org/licenses/gpl-2.0.html',
	tags: parseKeywords( pkg.keywords ).join( ', ' ),
	contributors: [ 'flixos90' ].join( ', ' ),
	donateLink: 'https://felix-arntz.me/wordpress-plugins/',
	minRequired: '4.9.6',
	testedUpTo: '4.9',
	requiresPHP: '7.0',
	translateURI: 'https://translate.wordpress.org/projects/wp-plugins/wp-gdpr-cookie-notice',
	network: false
};

/* ---- DO NOT EDIT BELOW THIS LINE ---- */

// WP plugin header for main plugin file
const pluginheader =' * Plugin Name: ' + config.pluginName + '\n' +
					' * Plugin URI:  ' + config.pluginURI + '\n' +
					' * Description: ' + config.description + '\n' +
					' * Version:     ' + config.version + '\n' +
					' * Author:      ' + config.author + '\n' +
					' * Author URI:  ' + config.authorURI + '\n' +
					' * License:     ' + config.license + '\n' +
					' * License URI: ' + config.licenseURI + '\n' +
					' * Text Domain: ' + config.pluginSlug + '\n' +
					( config.network ? ' * Network:     true' + '\n' : '' ) +
					' * Tags:        ' + config.tags;

// WP plugin header for readme.txt
const readmeheader ='Plugin Name:       ' + config.pluginName + '\n' +
					'Plugin URI:        ' + config.pluginURI + '\n' +
					'Author:            ' + config.author + '\n' +
					'Author URI:        ' + config.authorURI + '\n' +
					'Contributors:      ' + config.contributors + '\n' +
					( config.donateLink ? 'Donate link:       ' + config.donateLink + '\n' : '' ) +
					'Requires at least: ' + config.minRequired + '\n' +
					'Tested up to:      ' + config.testedUpTo + '\n' +
					( config.requiresPHP ? 'Requires PHP:      ' + config.requiresPHP + '\n' : '' ) +
					'Stable tag:        ' + config.version + '\n' +
					'Version:           ' + config.version + '\n' +
					'License:           ' + config.license + '\n' +
					'License URI:       ' + config.licenseURI + '\n' +
					'Tags:              ' + config.tags;


/* ---- REQUIRED DEPENDENCIES ---- */

const gulp    = require( 'gulp' );
const replace = require( 'gulp-replace' );

// build the plugin
gulp.task( 'build', [ 'readme-replace' ], () => {
	gulp.start( 'header-replace' );
});

// replace the plugin header in the main plugin file
gulp.task( 'header-replace', done => {
	gulp.src( './' + config.pluginSlug + '.php' )
		.pipe( replace( /(?:\s\*\s@wordpress-plugin\s(?:[^*]|(?:\*+[^*\/]))*\*+\/)/, ' * @wordpress-plugin\n' + pluginheader + '\n */' ) )
		.pipe( gulp.dest( './' ) )
		.on( 'end', done );
});

// replace the plugin header in readme.txt
gulp.task( 'readme-replace', done => {
	gulp.src( './readme.txt' )
		.pipe( replace( /\=\=\= (.+) \=\=\=([\s\S]+)\=\= Description \=\=/m, '=== ' + config.pluginName + ' ===\n\n' + readmeheader + '\n\n' + config.description + '\n\n== Description ==' ) )
		.pipe( gulp.dest( './' ) )
		.on( 'end', done );
});

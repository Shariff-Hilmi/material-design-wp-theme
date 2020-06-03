/* eslint-env node */

module.exports = function( grunt ) {
	'use strict';

	// prettier-ignore
	grunt.initConfig( {
		// Build a deploy-able plugin.
		copy: {
			build: {
				src: [
					'**',
					'!.*',
					'!.*/**',
					'!**/._.DS_Store',
					'!**/.DS_Store',
					'!assets/css/src/**',
					'!assets/js/.gitignore',
					'!assets/src/**',
					'!bin/**',
					'!build/**',
					'!built/**',
					'!code_of_conduct.md',
					'!contributing/**',
					'!composer.json',
					'!composer.lock',
					'!contributing.md',
					'!docker-compose.yml',
					'!docker-compose-plugin-dev.yml',
					'!material-theme.zip',
					'!Gruntfile.js',
					'!jest.config.js',
					'!node_modules/**',
					'!npm-debug.log',
					'!package.json',
					'!package-lock.json',
					'!phpcs.xml',
					'!phpcs.xml.dist',
					'!phpunit.xml',
					'!postcss.config.js',
					'!readme.md',
					'!renovate.json',
					'!tests/**',
					'!vendor/**',
					'!webpack.config.js',
				],
				dest: 'build',
				expand: true,
				dot: true,
			},
		},

		// Clean up the build.
		clean: {
			compiled: {
				src: [
					'assets/js/*.js',
					'!assets/js/admin.js',
					'assets/js/*.asset.php',
				],
			},
			build: {
				src: [ 'build' ],
			},
		},

		// Shell actions.
		shell: {
			options: {
				stdout: true,
				stderr: true,
			},
			readme: {
				command: './vendor/xwp/wp-dev-lib/scripts/generate-markdown-readme', // Generate the readme.md.
			},
			create_build_zip: {
				command: 'if [ ! -e build ]; then echo "Run grunt build first."; exit 1; fi; if [ -e material-theme.zip ]; then rm material-theme.zip; fi; cd build; zip -r ../material-theme.zip .; cd ..; echo; echo "ZIP of build: $(pwd)/material-theme.zip"',
			},
		},
	} );

	// Load tasks.
	grunt.loadNpmTasks( 'grunt-contrib-clean' );
	grunt.loadNpmTasks( 'grunt-contrib-copy' );
	grunt.loadNpmTasks( 'grunt-shell' );

	// Register tasks.
	grunt.registerTask( 'default', [ 'build' ] );

	grunt.registerTask( 'build', [ 'copy' ] );

	grunt.registerTask( 'create-build-zip', [ 'shell:create_build_zip' ] );

	grunt.registerTask( 'deploy', [ 'build', 'wp_deploy', 'clean' ] );
};

/**
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

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
					'!assets/css/*.map',
					'!assets/js/.gitignore',
					'!assets/js/*.php',
					'!assets/js/*.map',
					'!assets/src/**',
					'!bin/**',
					'!build/**',
					'!built/**',
					'!code_of_conduct.md',
					'!CONTRIBUTING.md',
					'!contributing/**',
					'!composer.json',
					'!composer.lock',
					'!contributing.md',
					'!docker-compose.yml',
					'!docker-compose-plugin-dev.yml',
					'!material-design-google.zip',
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
					'!README.md',
					'!renovate.json',
					'!tests/**',
					'!vendor/**',
					'!webpack.config.js',
					'!webpack/**',
					'!wp-assets/**',
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
					'assets/js/*.js.map',
					'assets/js/*.asset.php',
					'assets/css/*.css',
					'!assets/css/src/*',
					'assets/css/*.css.map',
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
				command: 'if [ ! -e build ]; then echo "Run grunt build first."; exit 1; fi; if [ -e material-design-google.zip ]; then rm material-design-google.zip; fi; mv build material-design-google; zip -r ./material-design-google.zip material-design-google; mv material-design-google build; echo; echo "ZIP of build: $(pwd)/material-design-google.zip"',
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

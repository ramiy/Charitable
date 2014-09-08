/*global module:false*/
/*global require:false*/
/*jshint -W097*/
"use strict";

module.exports = function(grunt) {
 
    // load all grunt tasks
    require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);
 
    grunt.initConfig({
 
        // watch for changes and trigger compass, jshint, uglify and livereload
        watch: {                        
            compass: {
                files: ['assets/compass/**/*.{scss,sass}'],
                tasks: ['compass']
            },
            js: {
                files: '<%= jshint.all %>',
                tasks: ['jshint', 'uglify']
            },
            css: {
                files: ['assets/css/*.css'],
                tasks: ['cssmin']
            },
            sync: {
                files: [
                    'assets', 
                    'assets/**', 
                    '!assets/compass', 
                    '!assets/compass/**', 
                    '!assets/scss', 
                    '!assets/scss/**',
                    'includes', 
                    'includes/**', 
                    'languages', 
                    'languages/**', 
                    'templates', 
                    'templates/**', 
                    'charitable.php'                    
                    // '**',
                    // '!.DS_Store',
                    // '!**/.DS_Store',
                    // '!.git*', 
                    // '!.jshintrc',
                    // '!.sass-cache',
                    // '!.sass-cache/**',
                    // '!node_modules', 
                    // '!node_modules/**', 
                    // '!Gruntfile.js', 
                    // '!package.json',                     
                    // '!README.md', 
                    // '!config.rb',                     
                    // '!assets/compass', 
                    // '!assets/compass/**', 
                    // '!assets/scss', 
                    // '!assets/scss/**',
                    // '!tests', 
                    // '!tests/**', 
                    // '!bin', 
                    // '!bin/**'
                ],
                tasks: ['sync:dist']
            }        
        },

        // Sync
        sync: {          
            dist: {
                files: [
                    // includes files within path
                    {
                        src: [  
                            'assets', 
                            'assets/**', 
                            '!assets/compass', 
                            '!assets/compass/**', 
                            '!assets/scss', 
                            '!assets/scss/**',
                            'includes', 
                            'includes/**', 
                            'languages', 
                            'languages/**', 
                            'templates', 
                            'templates/**', 
                            'charitable.php'
                            // 'assets/css/**', 
                            // ''
                            // '**',
                            // '!.DS_Store',
                            // '!**/.DS_Store',
                            // '!.git*', 
                            // '!.jshintrc',
                            // '!.sass-cache',
                            // '!.sass-cache/**',                  
                            // '!node_modules', 
                            // '!node_modules/**', 
                            // '!Gruntfile.js', 
                            // '!package.json',                     
                            // '!README.md', 
                            // '!config.rb',                     
                            // '!assets/compass', 
                            // '!assets/compass/**', 
                            // '!assets/scss', 
                            // '!assets/scss/**',
                            // '!tests', 
                            // '!tests/**', 
                            // '!vendor', 
                            // '!vendor/**'                            
                        ], 
                        dest: '/Users/ericdaams/Dropbox/Development/Projects/WP/wp-content/plugins/charitable'
                    }
                ], 
                verbose: true
            }
        },
 
        // compass and scss
        compass: {
            dist: {
                options: {
                    config: 'config.rb',
                    force: true
                }
            }
        },
 
        // javascript linting with jshint
        jshint: {
            options: {
                jshintrc: '.jshintrc',
                "force": true
            },
            all: [
                'Gruntfile.js'
            ]
        },        

        // uglify to concat, minify, and make source maps
        uglify: {
            dist: {
                files: {
                    'assets/js/charitable-admin.min.js': 'assets/js/charitable-admin.js'
                }
            }
        },

        // minify CSS
        cssmin: {
            minify: {
                files: {
                    'assets/css/main.min.css' : [ 
                        'assets/css/main.css'
                    ]
                }
            }
        },        

        // make POT file
        makepot: {
            target: {
                options: {
                    cwd: '',                        // Directory of files to internationalize.
                    domainPath: '/languages',       // Where to save the POT file.                    
                    mainFile: 'charitable.php',     // Main project file.
                    potFilename: 'charitable.pot',  // Name of the POT file.
                    type: 'wp-theme',               // Type of project (wp-plugin or wp-theme).
                    updateTimestamp: true           // Whether the POT-Creation-Date should be updated without other changes.
                }
            }
        }
    });

 
    // register task
    grunt.registerTask('default', ['watch']);
    grunt.registerTask('build', ['jshint', 'uglify', 'makepot']);
};
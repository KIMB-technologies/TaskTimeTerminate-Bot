<?php
/*
	Run check via Docker-Installed Phan:

	# function defined in shell
	phan() {
		docker run -v $PWD:/mnt/src --rm -u "1000:1000" phanphp/phan:latest $@; return $?;
	} 

	# start in document root of project
	$ phan -o report.txt 

	!! Make sure to have folder vendor in directory !!
		$ docker build .
		$ docker create -ti --name dummy <image> bash
		$ docker cp dummy:/code/vendor/ ./vendor 
*/
return [
	'target_php_version' => '8.0',
	'directory_list' => [
		'./bot/',
		'./vendor/'
	],
	'exclude_analysis_directory_list' => [
		'./vendor/'
	],
	'backward_compatibility_checks' => true,
	'plugins' => [
		'AlwaysReturnPlugin',
		'DollarDollarPlugin',
		'DuplicateArrayKeyPlugin',
		'DuplicateExpressionPlugin',
		'PregRegexCheckerPlugin',
		'PrintfCheckerPlugin',
		'SleepCheckerPlugin',
		'UnreachableCodePlugin',
		'UseReturnValuePlugin',
		'EmptyStatementListPlugin',
		'LoopVariableReusePlugin',
	],
	'suppress_issue_types' => [
		'PhanTypeArraySuspiciousNullable'
	]
];
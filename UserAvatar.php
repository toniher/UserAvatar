<?php

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This file is a MediaWiki extension, it is not a valid entry point' );
}

//self executing anonymous function to prevent global scope assumptions
call_user_func( function() {

	/** REGISTRATION */
	$GLOBALS['wgExtensionCredits']['parserhook'][] = array(
		'path' => __FILE__,
		'name' => 'UserAvatar',
		'version' => '0.1',
		'url' => 'https://www.mediawiki.org/wiki/Extension:UserAvatar',
		'author' => array( 'Toniher' ),
		'descriptionmsg' => 'useravatar_desc',
	);
	
	#http://www.mediawiki.org/wiki/Manual:$wgResourceModules
	$GLOBALS['wgResourceModules']['ext.UserAvatar'] = array(
		'localBasePath' => __DIR__,
		'scripts' => array( 'js/ext.UserAvatar.js' ),
		'styles' => array( 'css/ext.UserAvatar.css' ),
		'remoteExtPath' => 'UserAvatar'
	);
	
	/** LOADING OF CLASSES **/
	// https://www.mediawiki.org/wiki/Manual:$wgAutoloadClasses
	$GLOBALS['wgAutoloadClasses']['UserAvatar'] = __DIR__ . '/UserAvatar.classes.php';
	
	
	/** STRINGS AND THEIR TRANSLATIONS **/
	$GLOBALS['wgExtensionMessagesFiles']['UserAvatar'] = __DIR__ . '/UserAvatar.i18n.php';
	$GLOBALS['wgExtensionMessagesFiles']['UserAvatarMagic'] = __DIR__ . '/UserAvatar.i18n.magic.php';
	
	/** HOOKS **/
	
	#http://www.mediawiki.org/wiki/Manual:Hooks/ParserFirstCallInit
	#http://www.mediawiki.org/wiki/Manual:Tag_extensions
	$GLOBALS['wgHooks']['ParserFirstCallInit'][] = 'UserAvatarSetupTagExtension';
	
	#http://www.mediawiki.org/wiki/Manual:Parser_functions
	$GLOBALS['$wgHooks']['ParserFirstCallInit'][] = 'UserAvatarSetupParserFunction';
	
	#http://www.mediawiki.org/wiki/Manual:Hooks/SkinAfterContent
	# We put avatar at the end of articles created by one guy.
	$GLOBALS['wgHooks']['SkinAfterContent'][] = 'UserAvatar::onSkinAfterContent';
	
	#http://www.mediawiki.org/wiki/Manual:Hooks/OutputPageParserOutput
	# We put avatar only on User Page
	$GLOBALS['wgHooks']['OutputPageParserOutput'][] = 'UserAvatar::onOutputPageParserOutput';
	
	#http://www.mediawiki.org/wiki/Manual:Hooks/OutputPageBeforeHTML
	# We add this for loading CSS and JSS in every page by default
	$GLOBALS['wgHooks']['OutputPageBeforeHTML'][] = 'UserAvatar::onOutputPageBeforeHTML';
	
	#Ajax
	#https://www.mediawiki.org/wiki/Manual:$wgAjaxExportList
	$GLOBALS['wgAjaxExportList'][] = 'UserAvatar::getUserInfo';

});

// Hook our callback function into the parser
function UserAvatarSetupTagExtension( $parser ) {
	// When the parser sees the <userprofile> tag, it executes 
	// the printTag function (see below)
	$parser->setHook( 'userprofile', 'UserAvatar::printTag' );
	// Always return true from this function. The return value does not denote
	// success or otherwise have meaning - it just must always be true.
	return true;
}

// Hook our callback function into the parser
function UserAvatarSetupParserFunction( $parser ) {
	// When the parser sees the {{#userprofile:}} function, it executes 
	// the printFunction function (see below)
	$parser->setFunctionHook( 'userprofile', 'UserAvatar::printFunction', SFH_OBJECT_ARGS );
	// Always return true from this function. The return value does not denote
	// success or otherwise have meaning - it just must always be true.
	return true;
}




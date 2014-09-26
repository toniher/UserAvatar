<?php

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This file is a MediaWiki extension, it is not a valid entry point' );
}

/** REGISTRATION */
$wgExtensionCredits['parserhook'][] = array(
	'path' => __FILE__,
	'name' => 'UserAvatar',
	'version' => '0.1',
	'url' => 'https://www.mediawiki.org/wiki/Extension:UserAvatar',
	'author' => array( 'Toniher' ),
	'descriptionmsg' => 'useravatar_desc',
);

#http://www.mediawiki.org/wiki/Manual:$wgResourceModules
$wgResourceModules['ext.UserAvatar'] = array(
	'localBasePath' => __DIR__,
	'scripts' => array( 'js/ext.UserAvatar.js' ),
	'styles' => array( 'css/ext.UserAvatar.css' ),
	'remoteExtPath' => 'UserAvatar'
);

/** LOADING OF CLASSES **/
// https://www.mediawiki.org/wiki/Manual:$wgAutoloadClasses
$wgAutoloadClasses['UserAvatar'] = __DIR__ . '/UserAvatar.classes.php';
$wgAutoloadClasses['ApiUserAvatar'] = dirname( __FILE__ ). '/UserAvatar.api.php';

// API module
$wgAPIModules['useravatar'] = 'ApiUserAvatar';

/** STRINGS AND THEIR TRANSLATIONS **/
$wgExtensionMessagesFiles['UserAvatar'] = __DIR__ . '/UserAvatar.i18n.php';
$wgExtensionMessagesFiles['UserAvatarMagic'] = __DIR__ . '/UserAvatar.i18n.magic.php';

/** HOOKS **/

#http://www.mediawiki.org/wiki/Manual:Hooks/ParserFirstCallInit
#http://www.mediawiki.org/wiki/Manual:Tag_extensions
$wgHooks['ParserFirstCallInit'][] = 'UserAvatarSetupTagExtension';


// Hook our callback function into the parser
function UserAvatarSetupTagExtension( $parser ) {
	// When the parser sees the <userprofile> tag, it executes 
	// the printTag function (see below)
	$parser->setHook( 'userprofile', 'UserAvatar::printTag' );
	// Always return true from this function. The return value does not denote
	// success or otherwise have meaning - it just must always be true.
	return true;
}

#http://www.mediawiki.org/wiki/Manual:Parser_functions
$wgHooks['ParserFirstCallInit'][] = 'UserAvatarSetupParserFunction';


// Hook our callback function into the parser
function UserAvatarSetupParserFunction( $parser ) {
	// When the parser sees the {{#userprofile:}} function, it executes 
	// the printFunction function (see below)
	$parser->setFunctionHook( 'userprofile', 'UserAvatar::printFunction', SFH_OBJECT_ARGS );
	// Always return true from this function. The return value does not denote
	// success or otherwise have meaning - it just must always be true.
	return true;
}

#http://www.mediawiki.org/wiki/Manual:Hooks/SkinAfterContent
# We put avatar at the end of articles created by one guy.
$wgHooks['SkinAfterContent'][] = 'UserAvatar::onSkinAfterContent';

#http://www.mediawiki.org/wiki/Manual:Hooks/OutputPageParserOutput
# We put avatar only on User Page
$wgHooks['OutputPageParserOutput'][] = 'UserAvatar::onOutputPageParserOutput';

#http://www.mediawiki.org/wiki/Manual:Hooks/OutputPageBeforeHTML
# We add this for loading CSS and JSS in every page by default
$wgHooks['OutputPageBeforeHTML'][] = 'UserAvatar::onOutputPageBeforeHTML';

#http://www.mediawiki.org/wiki/Manual:Hooks/PageHistoryLineEnding
# We add avatar to the history listing
$wgHooks['PageHistoryLineEnding'][] = 'UserAvatar::onPageHistoryLineEnding';

#Ajax
#https://www.mediawiki.org/wiki/Manual:$wgAjaxExportList
$wgAjaxExportList[] = 'UserAvatar::getUserInfo';



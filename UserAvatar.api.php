<?php

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This file is a MediaWiki extension, it is not a valid entry point' );
}

class ApiUserAvatar extends ApiBase {

	public function execute() {

		$params = $this->extractRequestParams();

		// For compatibility with GET method, we process JSON
		$jsonresult = UserAvatar::apiOutput( $params['username'] );
		$output = json_decode( $jsonresult );

		$this->getResult()->addValue( null, $this->getModuleName(), array ( 'status' => $output->status, 'msg' => $output->msg ) );

		return true;

	}
	public function getAllowedParams() {
		return array(
			'username' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			)
		);
	}

	public function getDescription() {
		return array(
			'API for checking whether a user has an associated Avatar'
		);
	}
	public function getParamDescription() {
		return array(
			'username' => 'Username to be queried'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': 1.1';
	}
}

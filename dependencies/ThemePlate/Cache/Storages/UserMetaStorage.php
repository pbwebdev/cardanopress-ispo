<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

namespace CardanoPress\ISPO\Dependencies\ThemePlate\Cache\Storages;

class UserMetaStorage extends MetadataStorage {

	public function __construct() {

		parent::__construct( 'user' );

	}

}

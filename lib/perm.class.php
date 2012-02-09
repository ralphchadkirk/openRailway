<?php
	class Permissions extends Authentication
	{
        // Core OR permissions
		const LOGIN = 1;
		const MESSAGING = 2;
		const ADMIN = 4;
        const MODULES = 8;
		
		$simple_user = Permissions::LOGIN | Permissions::MESSAGING;
		$user = $simple_user | Permissions::MODULES;
		$admin = $user | Permissions::ADMIN;
    }
?>
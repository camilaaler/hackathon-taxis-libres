<?php
class ffAdminScreenHeaders extends ffAdminScreen implements ffIAdminScreen {
	public function getMenu() {
		$menu = $this->_getMenuObject();
		//add_menu_page($page_title, $menu_title, $capability, $menu_slug)
		$menu->pageTitle = 'Headers';
		$menu->menuTitle = 'Headers';
		$menu->type = ffMenu::TYPE_SUB_LEVEL;
		$menu->capability = 'manage_options';
//        $menu->menuSlug = 'headers_editor';
		$menu->parentSlug='ThemeDashboard';
		return $menu;
	}
}
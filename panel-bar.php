<?


class PanelBar {

  public static function show() {
    return '<div class="panelbar">' . self::content() . '</div>' . self::css();
  }

  protected static function css() {
    return '<style>'.tpl::load(__DIR__ . DS . 'assets' . DS . 'css' . DS . 'panelbar.css').'</style>';
  }

  protected static function content() {
    return self::languages() . self::logout();
  }

  protected static function logout() {
    $block  = '<div class="panelbar__btn panelbar__btn--logout">';
    $block .= '<a href="'.kirby()->urls()->index().'/panel/logout">Logout</a>';
    $block .= '</div>';
    return $block;
  }

  protected static function languages() {
    if ($languages = site()->languages()) {
      $block = '<div class="panelbar__btn panelbar__btn--lang">';
      foreach($languages as $language) {
        $block .= '<a href="' . $language->url() . '">' . $language->name() . '</a>';
      }
      $block .= '</div>';
      return $block;
    }
  }

}

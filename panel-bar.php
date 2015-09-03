<?


class PanelBar {

  public static $elements = array('languages', 'edit', 'logout');

  public static function show($elements = null) {
    if ($user = site()->user() and $user->hasPanelAccess()) {
      return '<div class="panelbar">' . self::content($elements) . '</div>' . self::css();
    }
  }

  protected static function css() {
    return '<style>'.tpl::load(__DIR__ . DS . 'assets' . DS . 'css' . DS . 'panelbar.css').'</style>';
  }

  protected static function content($elements = null) {
    $elements = is_null($elements) ? self::$elements : $elements;
    $content = '';
    foreach ($elements as $element) {
      if (is_callable($element)) {
        $content .= call_user_func($element);
      } elseif (is_callable(array('self', $element))) {
        $content .= call_user_func(array('self', $element));
      }
    }
    return $content;
  }

  protected static function edit() {
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

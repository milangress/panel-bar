<?


class PanelBar {

  public  $elements  = array('panel', 'edit', 'languages', 'logout');
  public  $site      = null;
  public  $page      = null;

  private $protected = array('show', 'css', 'content');


  public function __construct($elements = null) {
    $this->elements = is_array($elements) ? $elements : $this->elements;
    $this->site     = site();
    $this->page     = page();
  }

  public static function show($elements = null) {
    if ($user = site()->user() and $user->hasPanelAccess()) {
      $self = new self($elements);
      $bar  = '<div class="panelbar">'.$self->content().'</div>';
      $bar .= $self->css();
      return $bar;
    }
  }

  protected function content() {
    $content = '';
    foreach ($this->elements as $element) {

      // NEEDS SIMPLIFICATION
      // vvvvvvvv
      if(!in_array($element, $this->protected)) {
        if (is_callable($element)) {
          $content .= call_user_func($element);
        } elseif (is_callable(array('self', $element))) {
          $content .= call_user_func(array('self', $element));
        } else {
          $content .= $element;
        }
      } else {
        $content .= $element;
      }
      // ^^^^^^

    }
    return $content;
  }

  protected function edit() {
    $block  = '<div class="panelbar__btn">';
    $block .= '<a href="'.$this->site->url().'/panel/#/pages/show/'.$this->page->uri().'">Edit</a>';
    $block .= '</div>';
    return $block;
  }

  protected function logout() {
    $block  = '<div class="panelbar__btn panelbar__btn--logout">';
    $block .= '<a href="'.$this->site->url().'/panel/logout">Logout</a>';
    $block .= '</div>';
    return $block;
  }

  protected function languages() {
    if ($languages = $this->site->languages()) {
      $block = '<div class="panelbar__btn panelbar__btn--lang">';
      foreach($languages as $language) {
        $block .= '<a href="'.$language->url().'/'.$this->page->uri().'">'.$language->name().'</a>';
      }
      $block .= '</div>';
      return $block;
    }
  }

  protected function panel() {
    $block  = '<div class="panelbar__btn">';
    $block .= '<a href="'.site()->url().'/panel">Panel</a>';
    $block .= '</div>';
    return $block;
  }

  public function css() {
    $style = tpl::load(__DIR__ . DS . 'assets' . DS . 'css' . DS . 'panelbar.css');
    return '<style>'.$style.'</style>';
  }

}

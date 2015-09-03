<?


class PanelBar {

  public  $defaults  = array('panel', 'edit', 'toggle', 'languages', 'logout', 'user');
  public  $site      = null;
  public  $page      = null;
  public  $position  = null;

  private $assets    = __DIR__ . DS . 'assets';
  private $protected = array('__construct', 'defaults','show', 'content', 'css');


  public function __construct($elements = null) {
    $this->elements = is_array($elements) ? $elements : c::get('panelbar.elements', $this->defaults);
    $this->site     = site();
    $this->page     = page();
    $this->position = c::get('panelbar.position', 'top');
  }

  public static function show($elements = null) {
    if ($user = site()->user() and $user->hasPanelAccess()) {
      $self = new self($elements);
      $bar  = '<div class="panelbar '.$self->position.'">'.$self->content().'</div>';
      $bar .= $self->css();
      return $bar;
    }
  }

  protected function content() {
    $content = '';
    foreach ($this->elements as $element) {

      // $element is custom function
      if (is_callable($element)) {
        $content .= call_user_func($element);

      // $element is default function
      } elseif (is_callable(array('self', $element)) and !in_array($element, $this->protected)) {
        $content .= call_user_func(array('self', $element));

      // $element is a string
      } elseif (is_string($element)) {
        $content .= $element;
      }

    }
    return $content;
  }

  public static function link($args) {
    $block  = '<div class="panelbar__btn panelbar__btn--'.$args['id'].'">';
    $block .= '<a href="'.$args['url'].'">';
    $block .= '<i class="fa fa-'.$args['icon'].'"></i>';
    $block .= '<span>'.$args['text'].'</span>';
    $block .= '</a>';
    $block .= '</div>';
    return $block;
  }

  protected function panel() {
    return self::link(array(
      'id'   => 'panel',
      'icon' => 'cogs',
      'url'  => site()->url().'/panel',
      'text' => 'Panel'
    ));
  }

  protected function edit() {
    return self::link(array(
      'id'   => 'edit',
      'icon' => 'pencil-square-o',
      'url'  => $this->site->url().'/panel/#/pages/show/'.$this->page->uri(),
      'text' => 'Edit'
    ));
  }

  protected function toggle() {
    return self::link(array(
      'id'   => 'toggle',
      'icon' => $this->page->visible() ? 'toggle-on' : 'toggle-off',
      'url'  => $this->site->url().'/panel/#/pages/toggle/'.$this->page->uri(),
      'text' => $this->page->visible() ? 'Visible' : 'Invisible'
    ));
  }

  protected function user() {
    return self::link(array(
      'id'   => 'user',
      'icon' => 'user',
      'url'  => $this->site->url().'/panel/#/users/edit/'.$this->site->user(),
      'text' => $this->site->user()
    ));
  }

  protected function logout() {
    return self::link(array(
      'id'   => 'logout',
      'icon' => 'sign-out',
      'url'  => $this->site->url().'/panel/logout',
      'text' => 'Logout'
    ));
  }


  protected function languages() {
    if ($languages = $this->site->languages()) {
      $block  = '<div class="panelbar__btn panelbar__btn--lang">';

      // current language
      $block .= '<a href="'.$this->site->language()->url().'/'.$this->page->uri().'"><i class="fa fa-flag"></i><span>'.$this->site->language()->code().'</span></a>';

      // all other languages
      $block .= '<div class="panelbar__langs">';
      foreach($languages->not($this->site->language()->code()) as $language) {
        $block .= '<a href="'.$language->url().'/'.$this->page->uri().'" class="panelbar__lang">'.$language->code().'</a>';
      }
      $block .= '</div>';

      $block .= '</div>';
      return $block;
    }
  }

  protected function css() {
    $style  = tpl::load($this->assets . DS . 'css' . DS . 'panelbar.min.css');
    $style .= 'body {margin-'.$this->position.': 50px !important}';
    return '<style>'.$style.'</style>';
  }

  public static function defaults() {
    $self = new self();
    return $self->defaults;
  }

}

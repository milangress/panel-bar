<?


class PanelBar {

  public  $defaults  = array('panel', 'edit', 'languages', 'logout');
  public  $site      = null;
  public  $page      = null;

  private $assets    = __DIR__ . DS . 'assets';
  private $protected = array('__construct', 'defaults','show', 'content', 'css');


  public function __construct($elements = null) {
    $this->elements = is_array($elements) ? $elements : c::get('panelbar.elements', $this->defaults);
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

  protected function edit() {
    $block  = '<div class="panelbar__btn panelbar__btn--edit">';
    $block .= '<a href="'.$this->site->url().'/panel/#/pages/show/'.$this->page->uri().'"><span>Edit</span></a>';
    $block .= '</div>';
    return $block;
  }

  protected function logout() {
    $block  = '<div class="panelbar__btn panelbar__btn--logout">';
    $block .= '<a href="'.$this->site->url().'/panel/logout"><span>Logout</span></a>';
    $block .= '</div>';
    return $block;
  }

  protected function languages() {
    if ($languages = $this->site->languages()) {
      $block  = '<div class="panelbar__btn panelbar__btn--lang">';

      // current language
      $block .= '<a href="'.$this->site->language()->url().'/'.$this->page->uri().'"><span>'.$this->site->language()->code().'</span></a>';

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

  protected function panel() {
    $block  = '<div class="panelbar__btn panelbar__btn--panel">';
    $block .= '<a href="'.site()->url().'/panel"><span>Panel</span></a>';
    $block .= '</div>';
    return $block;
  }

  protected function css() {
    $style = tpl::load($this->assets . DS . 'css' . DS . 'panelbar.css');
    return '<style>'.$style.'</style>';
  }

  public static function defaults() {
    $self = new self();
    return $self->defaults;
  }

}

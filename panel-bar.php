<?php

class PanelBar {

  public  $defaults  = array('panel', 'edit', 'toggle', 'languages', 'logout', 'user');
  public  $site      = null;
  public  $page      = null;
  public  $position  = null;

  private $protected = array('__construct', 'defaults','show', 'content', 'css');


  public function __construct($elements = null) {
    $this->elements = is_array($elements) ? $elements : c::get('panelbar.elements', $this->defaults);
    $this->position = c::get('panelbar.position', 'top');

    $this->site     = site();
    $this->page     = page();
  }

  /* Public method to output panel bar */

  public static function show($elements = null, $css = true) {
    if ($user = site()->user() and $user->hasPanelAccess()) {
      if ($elements === true) $elements = self::defaults();
      $self = new self($elements);
      $bar  = '<div class="panelbar '.$self->position.'">'.$self->content().'</div>';
      if ($css) $bar .= $self->getCSS();
      return $bar;
    }
  }


  /* Get all elements together */

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


  /* Helpers */

  public static function float($args) {
    return (isset($args['float']) and $args['float'] !== false) ? 'panelbar__el--right' : '';
  }

  public static function link($args) {
    $class  = 'panelbar__btn '.self::float($args).' panelbar--'.$args['id'];
    $block  = '<div class="'.$class.'">';
    $block .= '<a href="'.$args['url'].'">';
    if (isset($args['icon'])) $block .= '<i class="fa fa-'.$args['icon'].'"></i>';
    if (isset($args['text'])) $block .= '<span>'.$args['text'].'</span>';
    $block .= '</a>';
    $block .= '</div>';
    return $block;
  }

  public static function dropdown($args) {
    $class  = 'panelbar__drop '.self::float($args).' panelbar--'.$args['id'];
    $block  = '<div class="'.$class.'">';

    // current item
    $block .= '<a href="'.$args['first']['url'].'">';
    if (isset($args['icon'])) $block .= '<i class="fa fa-'.$args['icon'].'"></i>';
    if (isset($args['first']['text'])) $block .= '<span>'.$args['first']['text'].'</span>';
    $block .= '<i class="fa fa-caret-'.(c::get('panelbar.position') == 'bottom' ? 'up' : 'down').' fa-styleless"></i>';
    $block .= '</a>';

    // all other items
    $block .= '<div class="panelbar__dropitems">';
    foreach($args['others'] as $item) {
      $block .= '<a href="'.$item['url'].'" class="panelbar__dropitem">'.$item['text'].'</a>';
    }
    $block .= '</div>';

    $block .= '</div>';
    return $block;
  }


  /* Elements */

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
      'id'    => 'user',
      'icon'  => 'user',
      'url'   => $this->site->url().'/panel/#/users/edit/'.$this->site->user(),
      'text'  => $this->site->user(),
      'float' => 'right'
    ));
  }

  protected function logout() {
    return self::link(array(
      'id'    => 'logout',
      'icon'  => 'power-off',
      'url'   => $this->site->url().'/panel/logout',
      'text'  => 'Logout',
      'float' => 'right'
    ));
  }


  protected function languages() {
    if ($languages = $this->site->languages()) {
      $items = array();
      foreach($languages->not($this->site->language()->code()) as $language) {
        array_push($items, array(
          'url' => $language->url().'/'.$this->page->uri(),
          'text' => $language->code()
        ));
      }

      return self::dropdown(array(
        'id'    => 'lang',
        'icon'  => 'flag',
        'first' => array(
                    'url' => $this->site->language()->url().'/'.$this->page->uri(),
                    'text' => $this->site->language()->code()
                   ),
        'others' => $items
      ));
    }
  }


  /* Assets */

  protected function getCSS($position = null) {
    $style  = tpl::load(__DIR__ . DS . 'assets' . DS . 'css' . DS . 'panelbar.min.css');
    $style .= 'body {margin-'.(is_null($position) ? $this->position : $position).': 50px !important}';
    return '<style>'.$style.'</style>';
  }

  public static function css() {
    $position = c::get('panelbar.position', 'top');
    $self = new self();
    return $self->getCSS();
  }

  public static function defaults() {
    $self = new self();
    return $self->defaults;
  }

}

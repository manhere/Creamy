<?php

require_once("config.php");
require_once("template.php");
require_once("htmlwriter/lib/phpHtmlWriter.php");

/**
 * Class for static html content of backend.
 */
class Backend {

  protected $html; // HTML writer to create little snippets.

  /**
   * Instantiate the backend.
   */
  public function __construct() {
    $this->html = new phpHtmlWriter();
  }

  /**
   * Show part of page using the provided template file.
   */
  public function show_part($template_file, $values = array()) {
    // Construct template name
    $template_name = $template_file . Config::$template_extension;

    // Load template
    $template = new Template($template_name);

    // Set placeholders in template with given values
    foreach ( $values as $name => $value ) {
      $template->Set($name, $value);
    }

    // Show output
    echo $template->Display();
  }

  public function msg_box($string) {
    print($this->html->tag("div.box", $string)); 
  }

  /**
   * Creates a html table containing the provided items.
   */
  private function table($items, $header, $classes=array()) {
    $list = ""; // Create an empty list

    // Create a table header
    $table_header = $this->html->tag("th", $header);
    $list .= $this->html->tag("tr", $table_header);

    foreach ($items as $item) {
      // Add item to list
      $row = $this->html->tag("td", $item);
      $list .= $this->html->tag("tr", $row);
    }
    return $this->html->tag("table" . implode($classes), $list);
  }

  /**
   * Shows a list of all editable content of a page.
   */
  private function list_contents() {
    // Find all content files in main folder 
    $contents = File::find("/" . Config::$extension . "/", "..");

    // Create the links
    $links = array();
    foreach ( $contents as $name => $path ) {
      $path = "editor.php?file=" . $path;
      $link = $this->html->tag("a href='$path'", $name) . "\n"; 
      array_push($links, $link);
    }
    print($this->table($links, "Select a content area to edit", array(".content-list")));
  }

  /**
   * Login page
   */
  public function show_login() {
    $this->show_part("header", array("title" => "Login"));
    $this->show_part("login");
    $this->show_part("footer");
  }

  /**
   * Admin page
   */
  public function show_backend() {
    $this->show_part("header", array("title" => "Administration"));
    $this->show_part("menu");
    $this->list_contents();
    $this->show_part("footer", array("loginstatus" => "Logged in as " . $_SESSION['username'] . "."));
  }
}
?>

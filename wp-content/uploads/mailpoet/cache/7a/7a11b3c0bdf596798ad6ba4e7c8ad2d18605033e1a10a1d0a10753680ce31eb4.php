<?php

/* form/editor.html */
class __TwigTemplate_e69f2dee35d36fe393d3d78a7eb43436a50b39d4c240ad00ae83d5352e4ee9ee extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.html", "form/editor.html", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'content' => array($this, 'block_content'),
            'templates' => array($this, 'block_templates'),
            'translations' => array($this, 'block_translations'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layout.html";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_title($context, array $blocks = array())
    {
        // line 4
        echo "<h1 class=\"title\">
  ";
        // line 5
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Form");
        echo "
  <a class=\"page-title-action\" href=\"?page=mailpoet-forms#/\">";
        // line 6
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Back");
        echo "</a>
</h1>
  <h2>
    <input
      type=\"text\"
      placeholder=\"";
        // line 11
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Click here to change the name");
        echo "\"
      id=\"mailpoet_form_name\"
      value=\"";
        // line 13
        echo twig_escape_filter($this->env, $this->getAttribute(($context["form"] ?? null), "name", array()), "html", null, true);
        echo "\"
    />
  </h2>
";
    }

    // line 18
    public function block_content($context, array $blocks = array())
    {
        // line 19
        echo "  <div id=\"mailpoet_form_wrapper\" class=\"clearfix\">
    <!-- Form Editor Container -->
    <div id=\"mailpoet_form_container\">
      <!-- Form Editor -->
      <div id=\"mailpoet_form_editor\">
          <div id=\"mailpoet_form_body\"></div>
          <p id=\"block_placeholder\" class=\"block_placeholder\"></p>
      </div>

      <p class=\"submit\">
        <a id=\"mailpoet_form_save\" href=\"javascript:;\" class=\"button button-primary\" >";
        // line 29
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Save");
        echo "</a>
      </p>
    </div>

    <!-- Form Editor: Toolbar -->
    <div id=\"mailpoet_form_toolbar\" style=\"visibility:hidden;\">
      <div class=\"mailpoet_toolbar_section closed\" data-section=\"settings\">
        <a href=\"javascript:;\" class=\"mailpoet_toggle\"><br /></a>
        <h3>";
        // line 37
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Settings");
        echo "</h3>

        <div>
          <!-- Form settings -->
          <form id=\"mailpoet_form_settings\" action=\"\" method=\"POST\">
            <input
              type=\"hidden\"
              id=\"mailpoet_form_id\"
              value=\"";
        // line 45
        echo twig_escape_filter($this->env, (($this->getAttribute(($context["form"] ?? null), "id", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["form"] ?? null), "id", array()), 0)) : (0)), "html", null, true);
        echo "\"
            />
            <div id=\"mailpoet_settings_segment_selection\">
              <!-- Form settings: list selection -->
              <p>
                <strong>";
        // line 50
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("This form adds the subscribers to these lists:");
        echo "</strong>
              </p>
              <select
                id=\"mailpoet_form_segments\"
                name=\"segments\"
                data-placeholder=\"";
        // line 55
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Please select a list");
        echo "\"
                multiple
                data-parsley-required-message=\"";
        // line 57
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Please select a list.");
        echo "\"
                required
              >
                ";
        // line 60
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["segments"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["segment"]) {
            // line 61
            echo "                  <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($context["segment"], "id", array()), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["segment"], "name", array()), "html", null, true);
            echo " (";
            echo twig_escape_filter($this->env, $this->getAttribute($context["segment"], "subscribers", array()), "html", null, true);
            echo ")</option>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['segment'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 63
        echo "              </select>
            </div>

            <div id=\"mailpoet_on_success\">
              <!-- Form settings: after submit -->
              <p>
                <label><strong>";
        // line 69
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("After submit...");
        echo "</strong></label>
                <label>
                  <input class=\"mailpoet_radio\"
                    type=\"radio\"
                    name=\"on_success\"
                    value=\"message\"
                    ";
        // line 75
        if ((twig_test_empty($this->getAttribute($this->getAttribute(($context["form"] ?? null), "settings", array()), "on_success", array())) || ($this->getAttribute($this->getAttribute(($context["form"] ?? null), "settings", array()), "on_success", array()) == "message"))) {
            // line 76
            echo "                      checked=\"checked\"
                    ";
        }
        // line 78
        echo "                  />";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Show message");
        echo "
                </label>
                &nbsp;
                <label>
                  <input class=\"mailpoet_radio\"
                    type=\"radio\"
                    name=\"on_success\"
                    value=\"page\"
                    ";
        // line 86
        if (($this->getAttribute($this->getAttribute(($context["form"] ?? null), "settings", array()), "on_success", array()) == "page")) {
            // line 87
            echo "                      checked=\"checked\"
                    ";
        }
        // line 89
        echo "                  />";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Go to Page");
        echo "
                </label>
              </p>
              <!-- default success message -->
              ";
        // line 93
        if ($this->getAttribute($this->getAttribute(($context["form"] ?? null), "settings", array()), "success_message", array())) {
            // line 94
            echo "                ";
            $context["success_message"] = $this->getAttribute($this->getAttribute(($context["form"] ?? null), "settings", array()), "success_message", array());
            // line 95
            echo "              ";
        } else {
            // line 96
            echo "                ";
            $context["success_message"] = $this->env->getExtension('MailPoet\Twig\I18n')->translate("Check your inbox to confirm your subscription.");
            // line 97
            echo "              ";
        }
        // line 98
        echo "
              <p
                id=\"mailpoet_on_success_message\"
                class=\"mailpoet_on_success_option\"
              >
                <textarea name=\"success_message\">";
        // line 103
        echo twig_escape_filter($this->env, ($context["success_message"] ?? null), "html", null, true);
        echo "</textarea>
              </p>
              <p
                id=\"mailpoet_on_success_page\"
                class=\"mailpoet_on_success_option\"
              >
                <select name=\"success_page\">
                  ";
        // line 110
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["pages"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["page"]) {
            // line 111
            echo "                    <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($context["page"], "id", array()), "html", null, true);
            echo "\"";
            // line 112
            if (($this->getAttribute($this->getAttribute(($context["form"] ?? null), "settings", array()), "success_page", array()) == $this->getAttribute($context["page"], "id", array()))) {
                // line 113
                echo " selected=\"selected\"";
            }
            // line 114
            echo ">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["page"], "title", array()), "html", null, true);
            echo "</option>
                  ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['page'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 116
        echo "                </select>
              </p>
            </div>
          </form>
        </div>
      </div>

      <!-- Toolbar: Shortcodes / Export -->
      <div class=\"mailpoet_toolbar_section closed\" data-section=\"shortcodes\">
        <a href=\"javascript:;\" class=\"mailpoet_toggle\"><br /></a>
        <h3>";
        // line 126
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Form Placement");
        echo "</h3>

        <div>
          <!-- Form export links -->
          <p>
            ";
        // line 131
        echo MailPoet\Util\Helpers::replaceLinkTags($this->env->getExtension('MailPoet\Twig\I18n')->translate("Add this form to your sidebar or footer on the [link]Widgets page[/link]."), "widgets.php", array("target" => "_blank"));
        // line 134
        echo "
          </p>
          <p>
            ";
        // line 137
        echo MailPoet\Util\Helpers::replaceLinkTags($this->env->getExtension('MailPoet\Twig\I18n')->translate("Copy and paste this [link]shortcode[/link] on to a post or page."), "javascript:;", array("class" => "mailpoet_form_export_toggle", "data-type" => "shortcode"));
        // line 140
        echo "
          </p>
          <p>
            ";
        // line 143
        echo sprintf($this->env->getExtension('MailPoet\Twig\I18n')->translate("%sPHP%s and %siFrame%s versions are also available."), "<a href=\"javascript:;\" class=\"mailpoet_form_export_toggle\" data-type=\"php\">", "</a>", "<a href=\"javascript:;\" class=\"mailpoet_form_export_toggle\" data-type=\"iframe\">", "</a>");
        // line 151
        echo "
         </p>

          <!-- Form export -->
          <div id=\"mailpoet_form_export\"></div>
        </div>
      </div>

        <!-- Toolbar: Fields -->
      <div class=\"mailpoet_toolbar_section closed\" data-section=\"fields\">
        <a href=\"javascript:;\" class=\"mailpoet_toggle\"><br /></a>
        <h3>";
        // line 162
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Fields");
        echo "</h3>

        <div>
          <ul id=\"mailpoet_toolbar_fields\">
          </ul>
          <p class=\"mailpoet_align_center\">
            <a id=\"mailpoet_form_add_field\" class=\"button button-primary\" href=\"javascript:;\" style=\"width:100%;\">";
        // line 168
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Add New Field");
        echo "</a>
          </p>
        </div>

      </div>

      <!-- Toolbar: Styles -->
      <div class=\"mailpoet_toolbar_section closed\" data-section=\"styles\">
        <a href=\"javascript:;\" class=\"mailpoet_toggle\"><br /></a>
        <h3>";
        // line 177
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Styles");
        echo "</h3>
        <div>
          <textarea id=\"mailpoet_form_styles\">";
        // line 179
        echo twig_escape_filter($this->env, ($context["styles"] ?? null), "html", null, true);
        echo "</textarea>
        <br />
        <p class=\"mailpoet_align_center\">
          <a
            id=\"mailpoet_form_preview\"
            href=\"javascript:;\"
            class=\"button button-primary\"
            style=\"width:100%;\"
          >";
        // line 187
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Preview");
        echo "</a>
        </p>
      </div>
        </div>

    <!-- End | Form Editor: Toolbar -->
    </div>

    <!-- Form Editor: History -->
    <div id=\"mailpoet_form_history\"></div>
  </div>

  ";
        // line 199
        echo $this->env->getExtension('MailPoet\Twig\Assets')->generateJavascript("vendor.js", "lib/prototype.min.js", "lib/scriptaculous.min.js", "form_editor.js");
        // line 204
        echo "

  <script type=\"text/javascript\">
    var mailpoet_segments = ";
        // line 207
        echo json_encode(($context["segments"] ?? null));
        echo ";

    var mailpoet_default_fields = [
      {
        id: 'divider',
        name: \"";
        // line 212
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Divider");
        echo "\",
        type: 'divider',
        multiple: true,
        readonly: true
      },
      {
        id: \"first_name\",
        name: \"";
        // line 219
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("First name");
        echo "\",
        type: 'text',
        params: {
          label: \"";
        // line 222
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("First name");
        echo "\"
        },
        readonly: true
      },
      {
        id: \"last_name\",
        name: \"";
        // line 228
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Last name");
        echo "\",
        type: 'text',
        params: {
          label: \"";
        // line 231
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Last name");
        echo "\"
        },
        readonly: true
      },
      {
        id: \"segments\",
        name: \"";
        // line 237
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("List selection");
        echo "\",
        type: 'segment',
        params: {
          label: \"";
        // line 240
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Select list(s):");
        echo "\"
        },
        readonly: true
      },
      {
        id: 'html',
        name: \"";
        // line 246
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Custom text or HTML");
        echo "\",
        type: 'html',
        params: {
            text: \"";
        // line 249
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Subscribe to our newsletter and join [mailpoet_subscribers_count] other subscribers.");
        echo "\"
        },
        multiple: true,
        readonly: true
      }
    ];

    jQuery(function(\$) {
      function mailpoet_form_toggle_segments() {
        // hide list selection if a list widget has been dragged into the editor
        \$('mailpoet_settings_segment_selection')[
          (WysijaForm.hasSegmentSelection())
          ? 'hide' : 'show'
        ]();
      }

      function mailpoet_form_fields() {
        // form editor: default fields
        var template = Handlebars.compile(\$('#form_template_fields').html());

        return MailPoet.Ajax.post({
          api_version: window.mailpoet_api_version,
          endpoint: 'customFields',
          action: 'getAll',
        }).done(function(response) {
          // render toolbar
          jQuery('#mailpoet_toolbar_fields').html(template({
            fields: \$.merge(
              \$.merge([], mailpoet_default_fields),
              response.data
            )
          }));

          setTimeout(function() {
            WysijaForm.init();
          }, 1);
        });
      }
      window.mailpoet_form_fields = mailpoet_form_fields;

      // enable code mirror editor on styles textarea
      MailPoet.CodeEditor = CodeMirror.fromTextArea(
        document.getElementById('mailpoet_form_styles'),
        {
          lineNumbers: true,
          tabMode: \"indent\",
          matchBrackets: true,
          theme: 'neo',
          mode: 'css'
        }
      );

      // toolbar sections
      \$(document).on(
        'click',
        '.mailpoet_toolbar_section.closed',
        mailpoet_toolbar_tab
      );

      function mailpoet_toolbar_tab(tab, callback) {
        var section = null;

        if(\$.type(tab) === 'string') {
          section = \$('.mailpoet_toolbar_section[data-section=\"'+tab+'\"]');
        } else {
          section = \$(this);
        }

        var current_section = \$('.mailpoet_toolbar_section:not(.closed)');

        if(current_section.data('section') === section.data('section')) {
          if(callback !== undefined && \$.type(callback) === 'function') {
            callback();
          }
        } else {
          // close currently opened section
          \$('.mailpoet_toolbar_section:not(.closed)').addClass('closed');

          // open selected section after a mini delay
          setTimeout(function() {
            \$(section).removeClass('closed');
            if(callback !== undefined && \$.type(callback) === 'function') {
              setTimeout(function() {
                callback();
              }, 151);
            }
          }.bind(this), 150);
        }
        return false;
      }

      // toolbar: open default section
      mailpoet_toolbar_tab('settings');

      // form: edit name (in place editor)
      \$('#mailpoet_form_edit_name').on('click', function() {
        mailpoet_edit_form_name();
        return false;
      });

      \$('#mailpoet_form_name_input').on('keypress', function(e) {
        if(e.which === 13) {
          mailpoet_edit_form_name();
          return false;
        }
      });

      function mailpoet_edit_form_name() {
        var is_editing = \$('#mailpoet_form_name')
          .data('mailpoet_editing') || false;

        if(!is_editing) {
          // set input value and show
          \$('#mailpoet_form_name_input')
            .val(\$('#mailpoet_form_name').text())
            .show();

          // set editing mode
          \$('#mailpoet_form_name').data('mailpoet_editing', true);

          // hide form name
          \$('#mailpoet_form_name').hide();

          // focus on text input
          \$('#mailpoet_form_name_input').focus();

          // set edit name label
          \$('#mailpoet_form_edit_name').html(\"";
        // line 376
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Save");
        echo "\");
        } else {
          var current_value = \$('#mailpoet_form_name').html(),
            new_value = \$('#mailpoet_form_name_input').val();

          // hide text input
          \$('#mailpoet_form_name_input').hide();

          // set value
          \$('#mailpoet_form_name').text(new_value);

          // set editing mode
          \$('#mailpoet_form_name').data('mailpoet_editing', false);

          // show form name
          \$('#mailpoet_form_name').show();

          // set edit name label
          \$('#mailpoet_form_edit_name').text(\"";
        // line 394
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Edit name");
        echo "\");

          // save change if necessary
          if(new_value !== '' && current_value !== new_value) {
            MailPoet.Ajax.post({
              api_version: window.mailpoet_api_version,
              endpoint: 'forms',
              action: 'save',
              data:  {
                id: \$('#mailpoet_form_id').val(),
                name: new_value
              }
            }).done(function(response) {
              MailPoet.Notice.success(
                \"";
        // line 408
        echo twig_escape_filter($this->env, twig_escape_filter($this->env, $this->env->getExtension('MailPoet\Twig\I18n')->translate("The form name was successfully updated!"), "js"), "html", null, true);
        echo "\"
              );
            }).fail(function(response) {
              if (response.errors.length > 0) {
                MailPoet.Notice.error(
                  response.errors.map(function(error) { return error.message; }),
                  { scroll: true }
                );
              }
            });
          }
        }
      }

      // on dom loaded
      \$(function() {
        // load form
        WysijaForm.load(";
        // line 425
        echo json_encode(($context["form"] ?? null));
        echo ");

        // save form
        \$('#mailpoet_form_save').on('click', function() {
          mailpoet_form_save();
          mailpoet_form_export();
          return false;
        });

        // edit name
        \$('#mailpoet_form_name').on('keyup', function(e) {
          if(e.which === 13) {
            \$('#mailpoet_form_save').trigger('click');
            this.blur();
          }
        });

        // preview form
        \$(document).on('click', '#mailpoet_form_preview', function() {
          mailpoet_form_preview();
          return false;
        });

        function mailpoet_form_preview() {
          MailPoet.Ajax.post({
            api_version: window.mailpoet_api_version,
            endpoint: 'forms',
            action: 'previewEditor',
            data: WysijaForm.save()
          }).done(function(response) {
            MailPoet.Modal.popup({
              title: \"";
        // line 456
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Form preview");
        echo "\",
              template: \$('#mailpoet_form_preview_template').html(),
              data: response.data
            });
          }).fail(function(response) {
            if (response.errors.length > 0) {
              MailPoet.Notice.error(
                response.errors.map(function(error) { return error.message; }),
                { scroll: true }
              );
            }
          });
        }

        function mailpoet_form_save(callback) {
          var form = WysijaForm.save();
          form.id = \$('#mailpoet_form_id').val();

          // reset error messages
          \$('#mailpoet_form_settings').parsley().reset();

          // validate segments to subscribe to
          if(WysijaForm.hasSegmentSelection()) {
            // validate segment selection in form
            if(WysijaForm.isSegmentSelectionValid() === false) {
              return false;
            }
          }

          // check if the form is valid
          if(\$('#mailpoet_form_settings').parsley().isValid() === false) {
            // refresh settings and trigger validation
            mailpoet_toolbar_tab('settings', function() {
              \$('#mailpoet_form_settings').parsley().validate();
            });
          } else {
            // save form
            MailPoet.Ajax.post({
              api_version: window.mailpoet_api_version,
              endpoint: 'forms',
              action: 'saveEditor',
              data: form
            }).done(function(response) {
              if(callback !== false) {
                var message = null;

                if(response.meta.is_widget === true) {
                  message = \"";
        // line 503
        echo twig_escape_filter($this->env, twig_escape_filter($this->env, $this->env->getExtension('MailPoet\Twig\I18n')->translate("Saved! The changes are now active in your widget."), "js"), "html", null, true);
        echo "\";
                } else {
                  message = \"";
        // line 505
        echo twig_escape_filter($this->env, twig_escape_filter($this->env, sprintf($this->env->getExtension('MailPoet\Twig\I18n')->translate("Saved! Add this form to %1\$sa widget%2\$s."), "<a href='widgets.php' target='_blank'>", "</a>"), "js"), "html", null, true);
        echo "\";
                }

                if(message !== null) {
                  MailPoet.Notice.hide();
                  MailPoet.Notice.success(message, {
                    scroll: true,
                    static: (response.meta.is_widget === false)
                  });
                }

                MailPoet.trackEvent('Forms > Add New', {
                  'MailPoet Free version': window.mailpoet_version
                });

                // if there is a callback, call it!
                if(callback !== undefined) {
                  callback();
                }
              }
            }).fail(function(response) {
              if (response.errors.length > 0) {
                MailPoet.Notice.error(
                  response.errors.map(function(error) { return error.message; }),
                  { scroll: true }
                );
              }
            });
          }
        }
        window.mailpoet_form_save = mailpoet_form_save;

        // toolbar: on success toggle
        \$(document).on('change', 'input[name=\"on_success\"]', toggleOnSuccessOptions);
        toggleOnSuccessOptions();

        function toggleOnSuccessOptions() {
          // hide all options
          \$('.mailpoet_on_success_option').hide();

          // display selected option
          var value = \$('input[name=\"on_success\"]:checked').val();
          \$('#mailpoet_on_success_'+value).show();
        }

        function mailpoet_form_export() {
          var template = Handlebars.compile(\$('#form_template_exports').html());
          MailPoet.Ajax.post({
            api_version: window.mailpoet_api_version,
            endpoint: 'forms',
            action: 'exportsEditor',
            data: {
              id: \$('#mailpoet_form_id').val()
            }
          }).done(function(response) {
            \$('#mailpoet_form_export').html(template({ exports: response.data }));
          }).fail(function(response) {
            if (response.errors.length > 0) {
              MailPoet.Notice.error(
                response.errors.map(function(error) { return error.message; }),
                { scroll: true }
              );
            }
          });
        }
        mailpoet_form_export();

        \$(document).on('click', '.mailpoet_form_export_toggle', function() {
          var type = \$(this).data('type');
          \$('.mailpoet_form_export_output').hide();
          \$('#mailpoet_form_export_' + type).show();
          MailPoet.trackEvent('Forms > Embed', {
            'Embed type': type,
            'MailPoet Free version': window.mailpoet_version
          });
          return false;
        });

        // add new field
        \$(document).on('click', '#mailpoet_form_add_field', function() {
          // open popup
          MailPoet.Modal.popup({
            title: \"";
        // line 587
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Add new field");
        echo "\",
            template: \$('#form_template_field_form').html()
          });

          return false;
        });

        // edit field
        \$(document).on('click', '.mailpoet_form_field_edit', function() {
          var id = \$(this).data('id');

          MailPoet.Ajax.post({
            api_version: window.mailpoet_api_version,
            endpoint: 'customFields',
            action: 'get',
            data: {
              id: id
            }
          }).done(function(response) {
            MailPoet.Modal.popup({
              title: \"";
        // line 607
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Edit field");
        echo "\",
              template: \$('#form_template_field_form').html(),
              data: response.data
            });
          }).fail(function(response) {
            if (response.errors.length > 0) {
              MailPoet.Notice.error(
                response.errors.map(function(error) { return error.message; }),
                { scroll: true }
              );
            }
          });
        });

        // delete field
        \$(document).on('click', '.mailpoet_form_field_delete', function() {
          var id = \$(this).data('id');
          var item = \$(this).parent();
          var name = \$(this).siblings('.mailpoet_form_field').attr('wysija_name');
          var type = \$(this).siblings('.mailpoet_form_field').attr('wysija_type');

          if(window.confirm(
            \"";
        // line 629
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("This field will be deleted for all your subscribers. Are you sure?");
        echo "\"
          )) {
            MailPoet.Ajax.post({
              api_version: window.mailpoet_api_version,
              endpoint: 'customFields',
              action: 'delete',
              data: {
                id: id
              }
            }).done(function(response) {
              item.remove();

              WysijaForm.removeBlock(id, function() {
                mailpoet_form_save(false);
              });

              mailpoet_form_fields();
              MailPoet.Notice.success(
                \"";
        // line 647
        echo twig_escape_filter($this->env, twig_escape_filter($this->env, $this->env->getExtension('MailPoet\Twig\I18n')->translate("Removed custom field %\$1s"), "js"), "html", null, true);
        echo "\".replace('%\$1s', '\"' + name + '\"')
              );

              MailPoet.trackEvent('Forms > Delete custom field', {
                'Field type': type,
                'MailPoet Free version': window.mailpoet_version
              });

            });
          }
        });

        // undo button
        \$(document).on('click', '#mailpoet_form_undo', function() {
          // pop last element off the history
          WysijaHistory.dequeue();

          return false;
        });

        // get form fields
        mailpoet_form_fields();

        // toolbar: segment selection
        var selected_segments = ";
        // line 671
        echo twig_jsonencode_filter($this->getAttribute($this->getAttribute(($context["form"] ?? null), "settings", array()), "segments", array()));
        echo ";

        //  enable select2 for segment selection
        var select2 = \$('#mailpoet_form_segments').select2({
          width:'100%',
          templateResult: function(item) {
            if(item.element && item.element.selected) {
              return null;
            } else {
              return item.text;
            }
          }
        });

        var hasRemoved = false;
        select2.on('select2:unselecting', function(e) {
          hasRemoved = true;
        });
        select2.on('select2:opening', function(e) {
          if(hasRemoved === true) {
            hasRemoved = false;
            e.preventDefault();
          }
        });

        // set selected values
        \$('#mailpoet_form_segments')
          .val(";
        // line 698
        echo twig_jsonencode_filter($this->getAttribute($this->getAttribute(($context["form"] ?? null), "settings", array()), "segments", array()));
        echo ")
          .trigger('change');
      });
    });
  </script>
";
    }

    // line 705
    public function block_templates($context, array $blocks = array())
    {
        // line 706
        echo "  <!-- toolbar templates -->
  ";
        // line 707
        echo $this->env->getExtension('MailPoet\Twig\Handlebars')->generatePartial($this->env, $context, "form_template_fields", "form/templates/toolbar/fields.hbs");
        echo "
  ";
        // line 708
        echo $this->env->getExtension('MailPoet\Twig\Handlebars')->generatePartial($this->env, $context, "form_template_exports", "form/templates/toolbar/exports.hbs");
        echo "

  <!-- block templates -->
  ";
        // line 711
        echo $this->env->getExtension('MailPoet\Twig\Handlebars')->generatePartial($this->env, $context, "form_template_block", "form/templates/blocks/container.hbs");
        echo "
  ";
        // line 712
        echo $this->env->getExtension('MailPoet\Twig\Handlebars')->generatePartial($this->env, $context, "form_template_divider", "form/templates/blocks/divider.hbs");
        echo "
  ";
        // line 713
        echo $this->env->getExtension('MailPoet\Twig\Handlebars')->generatePartial($this->env, $context, "form_template_text", "form/templates/blocks/text.hbs");
        echo "
  ";
        // line 714
        echo $this->env->getExtension('MailPoet\Twig\Handlebars')->generatePartial($this->env, $context, "form_template_submit", "form/templates/blocks/submit.hbs");
        echo "
  ";
        // line 715
        echo $this->env->getExtension('MailPoet\Twig\Handlebars')->generatePartial($this->env, $context, "form_template_segment", "form/templates/blocks/segment.hbs");
        echo "
  ";
        // line 716
        echo $this->env->getExtension('MailPoet\Twig\Handlebars')->generatePartial($this->env, $context, "form_template_radio", "form/templates/blocks/radio.hbs");
        echo "
  ";
        // line 717
        echo $this->env->getExtension('MailPoet\Twig\Handlebars')->generatePartial($this->env, $context, "form_template_select", "form/templates/blocks/select.hbs");
        echo "
  ";
        // line 718
        echo $this->env->getExtension('MailPoet\Twig\Handlebars')->generatePartial($this->env, $context, "form_template_checkbox", "form/templates/blocks/checkbox.hbs");
        echo "
  ";
        // line 719
        echo $this->env->getExtension('MailPoet\Twig\Handlebars')->generatePartial($this->env, $context, "form_template_textarea", "form/templates/blocks/textarea.hbs");
        echo "
  ";
        // line 720
        echo $this->env->getExtension('MailPoet\Twig\Handlebars')->generatePartial($this->env, $context, "form_template_html", "form/templates/blocks/html.hbs");
        echo "

  <!-- custom field settings and templates -->
  ";
        // line 723
        $this->loadTemplate("form/custom_fields.html", "form/editor.html", 723)->display($context);
        // line 724
        echo "
  <!-- form preview -->
  ";
        // line 726
        echo $this->env->getExtension('MailPoet\Twig\Handlebars')->generatePartial($this->env, $context, "mailpoet_form_preview_template", "form/templates/preview.hbs");
        // line 728
        echo "
";
    }

    // line 731
    public function block_translations($context, array $blocks = array())
    {
        // line 732
        echo "  ";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->localize(array("editFieldSettings" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Edit field settings")));
        // line 734
        echo "
";
    }

    public function getTemplateName()
    {
        return "form/editor.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  997 => 734,  994 => 732,  991 => 731,  986 => 728,  984 => 726,  980 => 724,  978 => 723,  972 => 720,  968 => 719,  964 => 718,  960 => 717,  956 => 716,  952 => 715,  948 => 714,  944 => 713,  940 => 712,  936 => 711,  930 => 708,  926 => 707,  923 => 706,  920 => 705,  910 => 698,  880 => 671,  853 => 647,  832 => 629,  807 => 607,  784 => 587,  699 => 505,  694 => 503,  644 => 456,  610 => 425,  590 => 408,  573 => 394,  552 => 376,  422 => 249,  416 => 246,  407 => 240,  401 => 237,  392 => 231,  386 => 228,  377 => 222,  371 => 219,  361 => 212,  353 => 207,  348 => 204,  346 => 199,  331 => 187,  320 => 179,  315 => 177,  303 => 168,  294 => 162,  281 => 151,  279 => 143,  274 => 140,  272 => 137,  267 => 134,  265 => 131,  257 => 126,  245 => 116,  236 => 114,  233 => 113,  231 => 112,  227 => 111,  223 => 110,  213 => 103,  206 => 98,  203 => 97,  200 => 96,  197 => 95,  194 => 94,  192 => 93,  184 => 89,  180 => 87,  178 => 86,  166 => 78,  162 => 76,  160 => 75,  151 => 69,  143 => 63,  130 => 61,  126 => 60,  120 => 57,  115 => 55,  107 => 50,  99 => 45,  88 => 37,  77 => 29,  65 => 19,  62 => 18,  54 => 13,  49 => 11,  41 => 6,  37 => 5,  34 => 4,  31 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "form/editor.html", "F:\\OpenServer\\domains\\orangesim\\wp-content\\plugins\\mailpoet\\views\\form\\editor.html");
    }
}

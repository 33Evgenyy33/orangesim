<?php

/* newsletter/templates/blocks/header/settings.hbs */
class __TwigTemplate_d9054222fcb028a18349b497f815e9d837684459858643643f1aa0de0239491c extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<h3>";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Header");
        echo "</h3>
<div class=\"mailpoet_form_field\">
    <div class=\"mailpoet_form_field_input_option\">
        <input type=\"text\" name=\"font-color\" id=\"mailpoet_field_header_text_color\" class=\"mailpoet_field_header_text_color mailpoet_color\" value=\"{{ model.styles.text.fontColor }}\" />
        <select id=\"mailpoet_field_header_text_font_family\" name=\"font-family\" class=\"mailpoet_select mailpoet_select_medium mailpoet_field_header_text_font_family mailpoet_font_family\">
        {{#each availableStyles.fonts}}
            <option value=\"{{ this }}\" {{#ifCond this '==' ../model.styles.text.fontFamily}}SELECTED{{/ifCond}}>{{ this }}</option>
        {{/each}}
        </select>
        <select id=\"mailpoet_field_header_text_size\" name=\"font-size\" class=\"mailpoet_select mailpoet_select_small mailpoet_field_header_text_size mailpoet_font_size\">
        {{#each availableStyles.textSizes}}
            <option value=\"{{ this }}\" {{#ifCond this '==' ../model.styles.text.fontSize}}SELECTED{{/ifCond}}>{{ this }}</option>
        {{/each}}
        </select>
    </div>
    <div class=\"mailpoet_form_field_title mailpoet_form_field_title_inline\">";
        // line 16
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Text");
        echo "</div>
</div>
<div class=\"mailpoet_form_field\">
      <div class=\"mailpoet_form_field_input_option\">
          <input type=\"text\" class=\"mailpoet_color\" size=\"6\" maxlength=\"6\" name=\"link-color\" value=\"{{ model.styles.link.fontColor }}\" id=\"mailpoet_field_header_link_color\" />
      </div>
      <div class=\"mailpoet_form_field_title mailpoet_form_field_title_inline\">";
        // line 22
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Links");
        echo "</div>
    <label>
        <div class=\"mailpoet_form_field_checkbox_option mailpoet_option_offset_left_small\">
            <input type=\"checkbox\" name=\"underline\" value=\"underline\" id=\"mailpoet_field_header_link_underline\" {{#ifCond model.styles.link.textDecoration '==' 'underline'}}CHECKED{{/ifCond}}/> ";
        // line 25
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Underline");
        echo "
        </div>
    </label>
</div>

<div class=\"mailpoet_form_field\">
    <div class=\"mailpoet_form_field_input_option\">
        <input type=\"text\" name=\"background-color\" class=\"mailpoet_field_header_background_color mailpoet_color\" value=\"{{ model.styles.block.backgroundColor }}\" />
    </div>
    <div class=\"mailpoet_form_field_title mailpoet_form_field_title_inline\">";
        // line 34
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Background");
        echo "</div>
</div>

<div class=\"mailpoet_form_field\">
    <div class=\"mailpoet_form_field_radio_option\">
        <label>
        <input type=\"radio\" name=\"alignment\" class=\"mailpoet_field_header_alignment\" value=\"left\" {{#ifCond model.styles.text.textAlign '===' 'left'}}CHECKED{{/ifCond}}/>
        ";
        // line 41
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Left");
        echo "
        </label>
    </div>
    <div class=\"mailpoet_form_field_radio_option\">
        <label>
            <input type=\"radio\" name=\"alignment\" class=\"mailpoet_field_header_alignment\" value=\"center\" {{#ifCond model.styles.text.textAlign '===' 'center'}}CHECKED{{/ifCond}}/>
            ";
        // line 47
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Center");
        echo "
        </label>
    </div>
    <div class=\"mailpoet_form_field_radio_option\">
        <label>
            <input type=\"radio\" name=\"alignment\" class=\"mailpoet_field_header_alignment\" value=\"right\" {{#ifCond model.styles.text.textAlign '===' 'right'}}CHECKED{{/ifCond}}/>
            ";
        // line 53
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Right");
        echo "
        </label>
    </div>
</div>

<div class=\"mailpoet_form_field\">
    <input type=\"button\" class=\"button button-primary mailpoet_done_editing\" value=\"";
        // line 59
        echo twig_escape_filter($this->env, $this->env->getExtension('MailPoet\Twig\I18n')->translate("Done"), "html_attr");
        echo "\" />
</div>
";
    }

    public function getTemplateName()
    {
        return "newsletter/templates/blocks/header/settings.hbs";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  102 => 59,  93 => 53,  84 => 47,  75 => 41,  65 => 34,  53 => 25,  47 => 22,  38 => 16,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "newsletter/templates/blocks/header/settings.hbs", "F:\\OpenServer\\domains\\orangesim\\wp-content\\plugins\\mailpoet\\views\\newsletter\\templates\\blocks\\header\\settings.hbs");
    }
}

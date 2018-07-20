<?php

/* newsletter/templates/blocks/button/settings.hbs */
class __TwigTemplate_eb6cd9e01807b080d09c821286ee718e1064dd0cc3a9f0bd36f82c1d1d0e1e32 extends Twig_Template
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
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Button");
        echo "</h3>
<div class=\"mailpoet_form_field\">
    <label>
        <div class=\"mailpoet_form_field_title mailpoet_form_field_title_inline\">";
        // line 4
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Label");
        echo "</div>
        <div class=\"mailpoet_form_field_input_option\">
            <input type=\"text\" name=\"text\" class=\"mailpoet_input mailpoet_field_button_text\" value=\"{{ model.text }}\" />
        </div>
    </label>
</div>

{{#ifCond renderOptions.hideLink '!==' true}}
<div class=\"mailpoet_form_field\">
    <label>
        <div class=\"mailpoet_form_field_title mailpoet_form_field_title_inline\">";
        // line 14
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Link");
        echo "</div>
        <div class=\"mailpoet_form_field_input_option\">
            <input type=\"text\" name=\"url\" class=\"mailpoet_input mailpoet_field_button_url\" value=\"{{ model.url }}\" placeholder=\"http://\" />
        </div>
    </label>
</div>
{{/ifCond}}

<div class=\"mailpoet_form_field\">
    <div class=\"mailpoet_form_field_title\">";
        // line 23
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Alignment");
        echo "</div>
    <div class=\"mailpoet_form_field_radio_option\">
        <label>
        <input type=\"radio\" name=\"alignment\" class=\"mailpoet_field_button_alignment\" value=\"left\" {{#ifCond model.styles.block.textAlign '===' 'left'}}CHECKED{{/ifCond}}/>
        ";
        // line 27
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Left");
        echo "
        </label>
    </div>
    <div class=\"mailpoet_form_field_radio_option\">
        <label>
            <input type=\"radio\" name=\"alignment\" class=\"mailpoet_field_button_alignment\" value=\"center\" {{#ifCond model.styles.block.textAlign '===' 'center'}}CHECKED{{/ifCond}}/>
            ";
        // line 33
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Center");
        echo "
        </label>
    </div>
    <div class=\"mailpoet_form_field_radio_option\">
        <label>
            <input type=\"radio\" name=\"alignment\" class=\"mailpoet_field_button_alignment\" value=\"right\" {{#ifCond model.styles.block.textAlign '===' 'right'}}CHECKED{{/ifCond}}/>
            ";
        // line 39
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Right");
        echo "
        </label>
    </div>
</div>
<div class=\"mailpoet_form_field\">
    <div class=\"mailpoet_form_field_input_option\">
        <input type=\"text\" name=\"font-color\" id=\"mailpoet_field_button_font_color\" class=\"mailpoet_field_button_font_color mailpoet_color\" value=\"{{ model.styles.block.fontColor }}\" />
        <select id=\"mailpoet_field_button_font_family\" name=\"font-family\" class=\"mailpoet_select mailpoet_select_medium mailpoet_field_button_font_family mailpoet_font_family\">
        {{#each availableStyles.fonts}}
            <option value=\"{{ this }}\" {{#ifCond this '==' ../model.styles.block.fontFamily}}SELECTED{{/ifCond}}>{{ this }}</option>
        {{/each}}
        </select>
        <select id=\"mailpoet_field_button_font_size\" name=\"font-size\" class=\"mailpoet_select mailpoet_select_small mailpoet_field_button_font_size mailpoet_font_size\">
        {{#each availableStyles.headingSizes}}
            <option value=\"{{ this }}\" {{#ifCond this '==' ../model.styles.block.fontSize}}SELECTED{{/ifCond}}>{{ this }}</option>
        {{/each}}
        </select>
    </div>
    <div class=\"mailpoet_form_field_title mailpoet_form_field_title_inline\">";
        // line 57
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Text");
        echo "</div>
</div>
<div class=\"mailpoet_form_field\">
    <div class=\"mailpoet_form_field_checkbox_option\">
        <label>
            <input type=\"checkbox\" name=\"fontWeight\" class=\"mailpoet_field_button_font_weight\" value=\"bold\" {{#ifCond styles.block.fontWeight '===' 'bold'}}CHECKED{{/ifCond}}/>
            ";
        // line 63
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Bold");
        echo "
        </label>
    </div>
</div>
<div class=\"mailpoet_form_field\">
    <div class=\"mailpoet_form_field_input_option\">
        <input type=\"text\" name=\"background-color\" class=\"mailpoet_field_button_background_color mailpoet_color\" value=\"{{ model.styles.block.backgroundColor }}\" />
    </div>
    <div class=\"mailpoet_form_field_title mailpoet_form_field_title_inline\">";
        // line 71
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Background");
        echo "</div>
</div>
<div class=\"mailpoet_form_field\">
    <div class=\"mailpoet_form_field_input_option\">
        <input type=\"text\" name=\"border-color\" class=\"mailpoet_field_button_border_color mailpoet_color\" value=\"{{ model.styles.block.borderColor }}\" />
    </div>
    <div class=\"mailpoet_form_field_title mailpoet_form_field_title_inline\">";
        // line 77
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Border");
        echo "</div>
    <div class=\"mailpoet_form_field_input_option\">
        <input type=\"number\" name=\"border-width-input\" class=\"mailpoet_input mailpoet_input_small mailpoet_field_button_border_width_input\" value=\"{{getNumber model.styles.block.borderWidth}}\" min=\"0\" max=\"10\" step=\"1\" /> px
        <input type=\"range\" min=\"0\" max=\"10\" step=\"1\" name=\"border-width\" class=\"mailpoet_range mailpoet_range_small mailpoet_field_button_border_width\" value=\"{{getNumber model.styles.block.borderWidth}}\" />
    </div>
</div>
<div class=\"mailpoet_form_field\">
    <label>
        <div class=\"mailpoet_form_field_title\">";
        // line 85
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Rounded corners");
        echo "</div>
        <div class=\"mailpoet_form_field_input_option\">
            <input type=\"number\" name=\"border-radius-input\" class=\"mailpoet_input mailpoet_input_small mailpoet_field_button_border_radius_input\" value=\"{{getNumber model.styles.block.borderRadius}}\" min=\"0\" max=\"40\" step=\"1\" /> px
            <input type=\"range\" min=\"0\" max=\"40\" step=\"1\" name=\"border-radius\" class=\"mailpoet_range mailpoet_range_medium mailpoet_field_button_border_radius\" value=\"{{getNumber model.styles.block.borderRadius }}\" />
        </div>
    </label>
</div>
<div class=\"mailpoet_form_field\">
    <label>
        <div class=\"mailpoet_form_field_title\">";
        // line 94
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Width");
        echo "</div>
        <div class=\"mailpoet_form_field_input_option\">
            <input type=\"number\" name=\"width-input\" class=\"mailpoet_input mailpoet_input_small mailpoet_field_button_width_input\" value=\"{{getNumber model.styles.block.width}}\" min=\"50\" max=\"288\" step=\"1\" /> px
            <input type=\"range\" min=\"50\" max=\"288\" step=\"1\" name=\"width\" class=\"mailpoet_range mailpoet_range_medium mailpoet_field_button_width\" value=\"{{getNumber model.styles.block.width }}\" />
        </div>
    </label>
</div>
<div class=\"mailpoet_form_field\">
    <label>
        <div class=\"mailpoet_form_field_title\">";
        // line 103
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Height");
        echo "</div>
        <div class=\"mailpoet_form_field_input_option\">
            <input type=\"number\" name=\"line-height-input\" class=\"mailpoet_input mailpoet_input_small mailpoet_field_button_line_height_input\" value=\"{{getNumber model.styles.block.lineHeight}}\" min=\"20\" max=\"50\" step=\"1\" /> px
            <input type=\"range\" min=\"20\" max=\"50\" step=\"1\" name=\"line-height\" class=\"mailpoet_range mailpoet_range_medium mailpoet_field_button_line_height\" value=\"{{getNumber model.styles.block.lineHeight }}\" />
        </div>
    </label>
</div>
{{#ifCond renderOptions.hideApplyToAll '!==' true}}
<div class=\"mailpoet_form_field\">
    <input type=\"button\" name=\"replace-all-button-styles\" class=\"button button-secondary mailpoet_button_full mailpoet_field_button_replace_all_styles\" value=\"";
        // line 112
        echo twig_escape_filter($this->env, $this->env->getExtension('MailPoet\Twig\I18n')->translate("Apply styles to all buttons"), "html_attr");
        echo "\" />
</div>
{{/ifCond}}

<div class=\"mailpoet_form_field\">
    <input type=\"button\" class=\"button button-primary mailpoet_done_editing\" value=\"";
        // line 117
        echo twig_escape_filter($this->env, $this->env->getExtension('MailPoet\Twig\I18n')->translate("Done"), "html_attr");
        echo "\" />
</div>

";
    }

    public function getTemplateName()
    {
        return "newsletter/templates/blocks/button/settings.hbs";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  181 => 117,  173 => 112,  161 => 103,  149 => 94,  137 => 85,  126 => 77,  117 => 71,  106 => 63,  97 => 57,  76 => 39,  67 => 33,  58 => 27,  51 => 23,  39 => 14,  26 => 4,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "newsletter/templates/blocks/button/settings.hbs", "F:\\OpenServer\\domains\\orangesim\\wp-content\\plugins\\mailpoet\\views\\newsletter\\templates\\blocks\\button\\settings.hbs");
    }
}

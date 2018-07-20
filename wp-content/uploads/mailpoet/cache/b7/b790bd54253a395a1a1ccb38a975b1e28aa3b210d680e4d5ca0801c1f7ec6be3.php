<?php

/* newsletter/templates/blocks/social/settingsIcon.hbs */
class __TwigTemplate_be007b86260e369c10a5d84c63f5e955d5973bff1c7da417aad7c6b99abd59a7 extends Twig_Template
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
        echo "<div class=\"mailpoet_social_icon_settings\">
    <div class=\"mailpoet_social_icon_settings_tool mailpoet_social_icon_settings_move_icon\">
        <a href=\"javascript:;\" class=\"mailpoet_move_block\">";
        // line 3
        echo twig_source($this->env, "newsletter/templates/svg/block-tools/move-without-bg.svg");
        echo "</a>
    </div>
    <div class=\"mailpoet_social_icon_settings_tool mailpoet_social_icon_settings_delete_icon\">
        <a href=\"javascript:;\" class=\"mailpoet_delete_block\">";
        // line 6
        echo twig_source($this->env, "newsletter/templates/svg/block-tools/trash-without-bg.svg");
        echo "</a>
    </div>
    <div class=\"mailpoet_social_icon_settings_row\">
        <label>
        <div class=\"mailpoet_social_icon_settings_label mailpoet_social_icon_image_label\">
            <img src=\"{{ model.image }}\" onerror=\"if (this.src != '{{ allIconSets.default.custom }}') this.src = '{{ allIconSets.default.custom }}';\" alt=\"{{ model.text }}\" class=\"mailpoet_social_icon_image\" />
        </div>
        <div class=\"mailpoet_social_icon_settings_form_element\">
            <select name=\"iconType\" class=\"mailpoet_social_icon_field_type\">
            {{#each iconTypes}}
                <option value=\"{{ iconType }}\" {{#ifCond iconType '==' ../model.iconType}}SELECTED{{/ifCond}}>{{ title }}</option>
            {{/each}}
            </select>
        </div>
        </label>
    </div>

    {{#ifCond iconType '==' 'custom'}}
    <div class=\"mailpoet_social_icon_settings_row\">
        <label>
        <div class=\"mailpoet_social_icon_settings_label\">
            ";
        // line 27
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Image");
        echo "
        </div>
        <div class=\"mailpoet_social_icon_settings_form_element\">
            <input type=\"text\" name=\"image\" class=\"mailpoet_social_icon_field_image\" value=\"{{ model.image }}\" placeholder=\"http://\" />
        </div>
        </label>
    </div>
    {{/ifCond}}

    <div class=\"mailpoet_social_icon_settings_row\">
        <label>
        <div class=\"mailpoet_social_icon_settings_label\">
            {{ currentType.linkFieldName }}
        </div>
        <div class=\"mailpoet_social_icon_settings_form_element\">
            {{#ifCond iconType '==' 'email'}}
            <input type=\"text\" name=\"link\" class=\"mailpoet_social_icon_field_link\" value=\"{{emailFromMailto model.link }}\" placeholder=\"example@example.org\" /><br />
            {{else}}
            <input type=\"text\" name=\"link\" class=\"mailpoet_social_icon_field_link\" value=\"{{ model.link }}\" placeholder=\"http://\" /><br />
            {{/ifCond}}
        </div>
        </label>
    </div>

    {{#ifCond iconType '==' 'custom'}}
    <div class=\"mailpoet_social_icon_settings_row\">
        <label>
        <div class=\"mailpoet_social_icon_settings_label\">
            ";
        // line 55
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Text");
        echo "
        </div>
        <div class=\"mailpoet_social_icon_settings_form_element\">
            <input type=\"text\" name=\"text\" class=\"mailpoet_social_icon_field_text\" value=\"{{ model.text }}\" />
        </div>
        </label>
    </div>
    {{/ifCond}}
</div>
";
    }

    public function getTemplateName()
    {
        return "newsletter/templates/blocks/social/settingsIcon.hbs";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  84 => 55,  53 => 27,  29 => 6,  23 => 3,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "newsletter/templates/blocks/social/settingsIcon.hbs", "F:\\OpenServer\\domains\\orangesim\\wp-content\\plugins\\mailpoet\\views\\newsletter\\templates\\blocks\\social\\settingsIcon.hbs");
    }
}

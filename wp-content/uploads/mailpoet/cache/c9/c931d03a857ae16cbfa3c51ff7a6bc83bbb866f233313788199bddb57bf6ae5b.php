<?php

/* newsletter/templates/blocks/social/settings.hbs */
class __TwigTemplate_4b8043199dfd7a0a67d25f7c0b704e85519695318fa60f1327e083f1a1fcf9c5 extends Twig_Template
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
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Select icons");
        echo "</h3>
<div id=\"mailpoet_social_icons_selection\"></div>
<h3>";
        // line 3
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Styles");
        echo "</h3>
<div id=\"mailpoet_social_icons_styles\"></div>
<div class=\"mailpoet_form_field\">
  <input type=\"button\" class=\"button button-primary mailpoet_done_editing\" value=\"";
        // line 6
        echo twig_escape_filter($this->env, $this->env->getExtension('MailPoet\Twig\I18n')->translate("Done"), "html_attr");
        echo "\" />
</div>
";
    }

    public function getTemplateName()
    {
        return "newsletter/templates/blocks/social/settings.hbs";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  31 => 6,  25 => 3,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "newsletter/templates/blocks/social/settings.hbs", "F:\\OpenServer\\domains\\orangesim\\wp-content\\plugins\\mailpoet\\views\\newsletter\\templates\\blocks\\social\\settings.hbs");
    }
}

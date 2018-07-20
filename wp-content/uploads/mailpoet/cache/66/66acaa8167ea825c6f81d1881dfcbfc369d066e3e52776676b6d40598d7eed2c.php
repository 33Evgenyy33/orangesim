<?php

/* newsletter/templates/blocks/posts/settings.hbs */
class __TwigTemplate_9e28e523c9ca29ea64d52a73c937d04aec12caeb59e987a959bfbf0095b3c7ac extends Twig_Template
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
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Post selection");
        echo "</h3>
<div class=\"mailpoet_settings_posts_selection\"></div>
<div class=\"mailpoet_settings_posts_display_options mailpoet_closed\"></div>
<div class=\"mailpoet_settings_posts_controls\">
  <div class=\"mailpoet_form_field\">
      <a href=\"javascript:;\" class=\"mailpoet_settings_posts_show_post_selection mailpoet_hidden\">";
        // line 6
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Back to selection");
        echo "</a>
      <a href=\"javascript:;\" class=\"mailpoet_settings_posts_show_display_options\">";
        // line 7
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Display options");
        echo "</a>
  </div>
  <input type=\"button\" class=\"button button-primary mailpoet_settings_posts_insert_selected\" value=\"";
        // line 9
        echo twig_escape_filter($this->env, $this->env->getExtension('MailPoet\Twig\I18n')->translate("Insert selected"), "html_attr");
        echo "\" />
</div>
";
    }

    public function getTemplateName()
    {
        return "newsletter/templates/blocks/posts/settings.hbs";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  37 => 9,  32 => 7,  28 => 6,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "newsletter/templates/blocks/posts/settings.hbs", "F:\\OpenServer\\domains\\orangesim\\wp-content\\plugins\\mailpoet\\views\\newsletter\\templates\\blocks\\posts\\settings.hbs");
    }
}

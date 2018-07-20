<?php

/* newsletter/templates/components/heading.hbs */
class __TwigTemplate_7003e5d29f726348d4b8500534eaa21e8fc3ee1a5bb6df5ff2938ec717020cba extends Twig_Template
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
        echo "<div class=\"mailpoet_form_field mailpoet_heading_form_field\">
  <input
    type=\"text\"
    class=\"mailpoet_input mailpoet_input_title\"
    data-automation-id=\"newsletter_title\"
    value=\"{{ model.subject }}\"
    placeholder=\"";
        // line 7
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Click here to change the subject!");
        echo "\"
  />
  <span id=\"tooltip-designer-subject-line\" class=\"tooltip-help-designer-subject-line\"></span>
</div>
<div class=\"mailpoet_form_field mailpoet_heading_form_field\">
  <input type=\"text\"
    class=\"mailpoet_input mailpoet_input_preheader\"
    value=\"{{ model.preheader }}\"
    placeholder=\"";
        // line 15
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Preview text (usually displayed underneath the subject line in the inbox)");
        echo "\"
  />
  <span id=\"tooltip-designer-preheader\" class=\"tooltip-help-designer-preheader\"></span>
</div>
";
    }

    public function getTemplateName()
    {
        return "newsletter/templates/components/heading.hbs";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  38 => 15,  27 => 7,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "newsletter/templates/components/heading.hbs", "F:\\OpenServer\\domains\\orangesim\\wp-content\\plugins\\mailpoet\\views\\newsletter\\templates\\components\\heading.hbs");
    }
}

<?php

/* form/templates/blocks/segment.hbs */
class __TwigTemplate_9d6fe959707a376994850c02eeedd8ba5e1ace0071304443aef1f3add76666b8 extends Twig_Template
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
        echo "{{#if params.label}}<p>{{ params.label }}</p>{{/if}}
{{#unless params.values}}<p class=\"mailpoet_error\">";
        // line 2
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Please select at least one list");
        echo "</p>{{/unless}}
{{#each params.values}}
  <p>
    <input class=\"mailpoet_checkbox\" type=\"checkbox\" {{#if is_checked}}checked=\"checked\"{{/if}} disabled=\"disabled\" />{{ name }}
  </p>
{{/each}}";
    }

    public function getTemplateName()
    {
        return "form/templates/blocks/segment.hbs";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  22 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "form/templates/blocks/segment.hbs", "F:\\OpenServer\\domains\\orangesim\\wp-content\\plugins\\mailpoet\\views\\form\\templates\\blocks\\segment.hbs");
    }
}

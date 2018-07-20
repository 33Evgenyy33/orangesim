<?php

/* newsletter/templates/blocks/image/block.hbs */
class __TwigTemplate_421c656a794c276380bf34ac4a2d74307e231b6bb3e0b3b895de8c5370fb3893 extends Twig_Template
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
        echo "<div class=\"mailpoet_tools\"></div>
<div class=\"mailpoet_content\" style=\"{{#ifCond model.styles.block.textAlign '==' 'left'}}margin: 0 auto 0 0; {{/ifCond}}{{#ifCond model.styles.block.textAlign '==' 'center'}}margin: auto; {{/ifCond}}{{#ifCond model.styles.block.textAlign '==' 'right'}}margin: 0 0 0 auto; {{/ifCond}}width: {{model.width}}\">
\t<div class=\"mailpoet_image\">
\t\t<a href=\"{{ model.link }}\" onClick=\"return false;\">
\t\t\t<img src=\"{{#ifCond model.src '!=' ''}}{{ model.src }}{{ else }}{{ imageMissingSrc }}{{/ifCond}}\" alt=\"{{ model.alt }}\" onerror=\"if(this.src != '{{ imageMissingSrc }}') {this.src = '{{ imageMissingSrc }}'; this.style.width='auto';}\" width=\"{{model.width}}\" />
\t\t</a>
\t\t<div class=\"mailpoet_image_resize_handle_container\">
\t\t    <div class=\"mailpoet_image_resize_handle\">
\t\t        <span class=\"mailpoet_image_resize_handle_icon\">";
        // line 9
        echo twig_source($this->env, "newsletter/templates/svg/block-tools/resize.svg");
        echo "</span>
\t\t    </div>
\t\t</div>
\t</div>
</div>
<div class=\"mailpoet_block_highlight\"></div>
";
    }

    public function getTemplateName()
    {
        return "newsletter/templates/blocks/image/block.hbs";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  29 => 9,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "newsletter/templates/blocks/image/block.hbs", "F:\\OpenServer\\domains\\orangesim\\wp-content\\plugins\\mailpoet\\views\\newsletter\\templates\\blocks\\image\\block.hbs");
    }
}

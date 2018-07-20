<?php

/* newsletter/templates/blocks/spacer/block.hbs */
class __TwigTemplate_6e19431607a216ac0068f9775f0265fd04660a975d37cee97afc86f825fb6488 extends Twig_Template
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
<div class=\"mailpoet_content\">
    <div class=\"mailpoet_spacer\" style=\"height: {{ model.styles.block.height }}; background-color: {{ model.styles.block.backgroundColor }};\">
        <div class=\"mailpoet_resize_handle_container\">
            <div class=\"mailpoet_resize_handle\">
                <span class=\"mailpoet_resize_handle_text\">{{ model.styles.block.height }}</span>
                <span class=\"mailpoet_resize_handle_icon\">";
        // line 7
        echo twig_source($this->env, "newsletter/templates/svg/block-icons/spacer.svg");
        echo "</span>
            </div>
        </div>
    </div>
</div>
<div class=\"mailpoet_block_highlight\"></div>
";
    }

    public function getTemplateName()
    {
        return "newsletter/templates/blocks/spacer/block.hbs";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  27 => 7,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "newsletter/templates/blocks/spacer/block.hbs", "F:\\OpenServer\\domains\\orangesim\\wp-content\\plugins\\mailpoet\\views\\newsletter\\templates\\blocks\\spacer\\block.hbs");
    }
}

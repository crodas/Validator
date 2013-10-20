<?php
/**
 *  This file was generated with crodas/SimpleView (https://github.com/crodas/SimpleView)
 *  Do not edit this file.
 *
 */

namespace {

    class base_template_ccee76bf6d3440b3ca1ee932ed0a7ef971047ccf
    {
        protected $parent;
        protected $child;
        protected $context;

        public function yield_parent($name, $args)
        {
            $method = "section_" . sha1($name);

            if (is_callable(array($this->parent, $method))) {
                $this->parent->$method(array_merge($this->context, $args));
                return true;
            }

            if ($this->parent) {
                return $this->parent->yield_parent($name, $args);
            }

            return false;
        }

        public function do_yield($name, Array $args = array())
        {
            if ($this->child) {
                // We have a children template, we are their base
                // so let's see if they have implemented by any change
                // this section
                if ($this->child->do_yield($name, $args)) {
                    // yes!
                    return true;
                }
            }

            // Do I have this section defined?
            $method = "section_" . sha1($name);
            if (is_callable(array($this, $method))) {
                // Yes!
                $this->$method(array_merge($this->context, $args));
                return true;
            }

            // No :-(
            return false;
        }

    }

    /** 
     *  Template class generated from Error.tpl
     */
    class class_873c1a91cea8cbb245abe554d24748c8fe4e84b9 extends base_template_ccee76bf6d3440b3ca1ee932ed0a7ef971047ccf
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            if ($self->msg) {
                echo "if (!" . ($self->result) . ") {\n        throw new \\UnexpectedValueException(_(\"";
                echo htmlentities($self->msg, ENT_QUOTES, 'UTF-8', false);
                echo "\"));\n}\n";
            }

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from Optional.tpl
     */
    class class_73177f083ce4946c03c09151e30c22c4883d8a97 extends base_template_ccee76bf6d3440b3ca1ee932ed0a7ef971047ccf
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            echo "if (empty(" . ($input) . ")) {\n    return true;\n}\n" . ($self->result) . " = true;\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from Not.tpl
     */
    class class_24c861bd74b2967c8b4b91272f2165119e46e335 extends base_template_ccee76bf6d3440b3ca1ee932ed0a7ef971047ccf
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            echo $self->result . " = false;\n";
            foreach($args as $rule) {
                echo "    " . ( $rule->toCode($input) ) . "\n    if (" . ($rule->result) . ") {\n        " . ($self->result) . " = true;\n        goto exit_" . (sha1($self->result)) . ";\n    }\n";
            }
            echo "exit_" . (sha1($self->result)) . ":\n" . ($self->result) . " = !" . ($self->result) . ";\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from Alnum.tpl
     */
    class class_fda5329c4d3473f86f472bff037149dac04a7069 extends base_template_ccee76bf6d3440b3ca1ee932ed0a7ef971047ccf
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            echo $self->result . " = ctype_alnum(" . ($input) . ");\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from Positive.tpl
     */
    class class_a02f0cae0d7ce37d38d758f9164c2cdaad435964 extends base_template_ccee76bf6d3440b3ca1ee932ed0a7ef971047ccf
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            echo "\$is_valid = is_numeric(" . ($input) . ") && " . ($input) . " > 0;\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from AllOf.tpl
     */
    class class_a11236d6e933f6694cf823c635f715839924f36a extends base_template_ccee76bf6d3440b3ca1ee932ed0a7ef971047ccf
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            echo $self->result . " = true;\n";
            foreach($args as $rule) {
                echo "    " . ( $rule->toCode($input) ) . "\n    if (!" . ($rule->result) . ") {\n        " . ($self->result) . " = false;\n        goto exit_" . (sha1($self->result)) . ";\n    }\n";
            }
            echo "exit_" . (sha1($self->result)) . ":\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from Length.tpl
     */
    class class_87373bac58cc91a097cde8b6f75577026c5bdf85 extends base_template_ccee76bf6d3440b3ca1ee932ed0a7ef971047ccf
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            echo "\$len = strlen(" . ($input) . ");\n" . ($self->result) . " = \$len >= " . ($args[0]) . " && \$len <= " . ($args[1]) . ";\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from AnyOf.tpl
     */
    class class_f4f0cb85093b6835d6eb2a544e6065572b50cab6 extends base_template_ccee76bf6d3440b3ca1ee932ed0a7ef971047ccf
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            echo $self->result . " = false;\n";
            foreach($args as $rule) {
                echo "    " . ( $rule->toCode($input) ) . "\n    if (" . ($rule->result) . ") {\n        " . ($self->result) . " = true;\n        exit_" . (sha1($self->result)) . ";\n    }\n";
            }
            echo "exit_" . (sha1($self->result)) . ":\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from Between.tpl
     */
    class class_9f088ed2c64f7697e44a25fcb3616547625c8713 extends base_template_ccee76bf6d3440b3ca1ee932ed0a7ef971047ccf
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            echo  $self->result  . " = " . ($input) . " >= " . ($args[0]) . " && " . ($input) . " <= " . ($args[1]) . ";\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from Body.tpl
     */
    class class_98919c47cacf71f4af08a8b2c075ad7c19e2b5b1 extends base_template_ccee76bf6d3440b3ca1ee932ed0a7ef971047ccf
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            echo "<?php\n\n";
            if ($namespace) {
                echo "namespace " . ($namespace) . ";\n";
            }
            echo "\n";
            foreach($functions as $name => $body) {
                echo "function " . ($name) . " (" . ($var) . ")\n{\n    " . ($body->toCode($var)) . "\n    return " . ($body->result) . ";\n}\n\n";
            }
            echo "\nfunction validate(\$rule, \$input)\n{\n    switch (\$rule) {\n";
            foreach($funcmap as $name => $func) {
                echo "        case \"";
                echo htmlentities($name, ENT_QUOTES, 'UTF-8', false);
                echo "\":\n            \$valid = " . ($func) . "(\$input);\n            break;\n";
            }
            echo "        default:\n            throw new \\Exception(\"Cannot find validator for {\$rule}\");\n    }\n    return \$valid;\n\n}\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from NoWhitespace.tpl
     */
    class class_caea816a5db6e6c3998cf366128c6cf4cbec0c18 extends base_template_ccee76bf6d3440b3ca1ee932ed0a7ef971047ccf
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            echo $self->result . " = !preg_match('#\\s#', " . ($input) . ");\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from Email.tpl
     */
    class class_6a3c3199ac12f05b87a72f489af82723d8e98693 extends base_template_ccee76bf6d3440b3ca1ee932ed0a7ef971047ccf
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            echo $self->result . " = is_string(" . ($input) . ") && filter_var(" . ($input) . ", FILTER_VALIDATE_EMAIL);\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

}

namespace crodas\Validate {

    class Templates
    {
        public static function exec($name, Array $context = array(), Array $global = array())
        {
            $tpl = self::get($name);
            return $tpl->render(array_merge($global, $context));
        }

        public static function get($name, Array $context = array())
        {
            static $classes = array (
                'error.tpl' => 'class_873c1a91cea8cbb245abe554d24748c8fe4e84b9',
                'error' => 'class_873c1a91cea8cbb245abe554d24748c8fe4e84b9',
                'optional.tpl' => 'class_73177f083ce4946c03c09151e30c22c4883d8a97',
                'optional' => 'class_73177f083ce4946c03c09151e30c22c4883d8a97',
                'not.tpl' => 'class_24c861bd74b2967c8b4b91272f2165119e46e335',
                'not' => 'class_24c861bd74b2967c8b4b91272f2165119e46e335',
                'alnum.tpl' => 'class_fda5329c4d3473f86f472bff037149dac04a7069',
                'alnum' => 'class_fda5329c4d3473f86f472bff037149dac04a7069',
                'positive.tpl' => 'class_a02f0cae0d7ce37d38d758f9164c2cdaad435964',
                'positive' => 'class_a02f0cae0d7ce37d38d758f9164c2cdaad435964',
                'allof.tpl' => 'class_a11236d6e933f6694cf823c635f715839924f36a',
                'allof' => 'class_a11236d6e933f6694cf823c635f715839924f36a',
                'length.tpl' => 'class_87373bac58cc91a097cde8b6f75577026c5bdf85',
                'length' => 'class_87373bac58cc91a097cde8b6f75577026c5bdf85',
                'anyof.tpl' => 'class_f4f0cb85093b6835d6eb2a544e6065572b50cab6',
                'anyof' => 'class_f4f0cb85093b6835d6eb2a544e6065572b50cab6',
                'between.tpl' => 'class_9f088ed2c64f7697e44a25fcb3616547625c8713',
                'between' => 'class_9f088ed2c64f7697e44a25fcb3616547625c8713',
                'body.tpl' => 'class_98919c47cacf71f4af08a8b2c075ad7c19e2b5b1',
                'body' => 'class_98919c47cacf71f4af08a8b2c075ad7c19e2b5b1',
                'nowhitespace.tpl' => 'class_caea816a5db6e6c3998cf366128c6cf4cbec0c18',
                'nowhitespace' => 'class_caea816a5db6e6c3998cf366128c6cf4cbec0c18',
                'email.tpl' => 'class_6a3c3199ac12f05b87a72f489af82723d8e98693',
                'email' => 'class_6a3c3199ac12f05b87a72f489af82723d8e98693',
            );
            $name = strtolower($name);
            if (empty($classes[$name])) {
                throw new \RuntimeException("Cannot find template $name");
            }

            $class = "\\" . $classes[$name];
            return new $class;
        }
    }

}

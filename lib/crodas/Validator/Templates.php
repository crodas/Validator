<?php
/**
 *  This file was generated with crodas/SimpleView (https://github.com/crodas/SimpleView)
 *  Do not edit this file.
 *
 */

namespace {

    class base_template_20e5750a0f2104effacdf0b83f627f8af5fd0276
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
    class class_873c1a91cea8cbb245abe554d24748c8fe4e84b9 extends base_template_20e5750a0f2104effacdf0b83f627f8af5fd0276
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
                $__temporary = $self->msg;
                if (!empty($__temporary)) {
                    echo htmlentities($__temporary, ENT_QUOTES, 'UTF-8', false);
                }
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
    class class_73177f083ce4946c03c09151e30c22c4883d8a97 extends base_template_20e5750a0f2104effacdf0b83f627f8af5fd0276
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            echo $self->result . " = true;\nif (empty(" . ($input) . ")) {\n";
            if (!empty($parent)) {
                echo "        goto exit_" . (sha1($parent->result)) . ";\n";
            }
            else {
                echo "        return true;\n";
            }
            echo "}\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from MinLength.tpl
     */
    class class_f383bca0734f6fcb6b4d791ef8997c23e2b34e14 extends base_template_20e5750a0f2104effacdf0b83f627f8af5fd0276
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            echo $self->result . " = strlen(" . ($input) . ") >= " . ($args[0]) . ";\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from Not.tpl
     */
    class class_24c861bd74b2967c8b4b91272f2165119e46e335 extends base_template_20e5750a0f2104effacdf0b83f627f8af5fd0276
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
                echo "    " . ($rule->toCode($input, $self)) . "\n    if (" . ($rule->result) . ") {\n        " . ($self->result) . " = true;\n        goto exit_" . (sha1($self->result)) . ";\n    }\n";
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
    class class_fda5329c4d3473f86f472bff037149dac04a7069 extends base_template_20e5750a0f2104effacdf0b83f627f8af5fd0276
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
     *  Template class generated from Charset.tpl
     */
    class class_889c8c99df4438b8d719d56e98239001ce98b33d extends base_template_20e5750a0f2104effacdf0b83f627f8af5fd0276
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            echo $self->result . " = false;\nif (is_scalar(" . ($input) . ")) {\n    \$expected = ";
            var_export($args);
            echo ";\n    \$encoding = mb_detect_encoding(" . ($input) . ", \$expected, true);\n    " . ($self->result) . " = in_array(\$encoding, \$expected, true);\n}\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from CreditCard.tpl
     */
    class class_54c1f1fb9a99693048e9f640447249f3c659115e extends base_template_20e5750a0f2104effacdf0b83f627f8af5fd0276
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            echo "\$tmp = preg_replace('([^0-9])', '', " . ($input) . ");\nif (empty(\$tmp)) {\n    " . ($self->result) . " = false;\n} else {\n    \$sum = 0;\n    \$tmp = strrev(\$tmp);\n    for (\$i = 0; \$i < strlen(\$tmp); \$i++) {\n        \$current = substr(\$tmp, \$i, 1);\n        if (\$i % 2 == 1) {\n            \$current *= 2;\n            if (\$current > 9) {\n                \$firstDigit = \$current % 10;\n                \$secondDigit = (\$current - \$firstDigit) / 10;\n                \$current = \$firstDigit + \$secondDigit;\n            }\n        }\n        \$sum += \$current;\n    }\n\n    " . ($self->result) . " = (\$sum % 10 == 0);\n}\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from Positive.tpl
     */
    class class_a02f0cae0d7ce37d38d758f9164c2cdaad435964 extends base_template_20e5750a0f2104effacdf0b83f627f8af5fd0276
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            echo $self->result . " = is_numeric(" . ($input) . ") && " . ($input) . " > 0;\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from AllOf.tpl
     */
    class class_a11236d6e933f6694cf823c635f715839924f36a extends base_template_20e5750a0f2104effacdf0b83f627f8af5fd0276
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
                echo "    " . ($rule->toCode($input, $self)) . "\n    if (!" . ($rule->result) . ") {\n        " . ($self->result) . " = false;\n        goto exit_" . (sha1($self->result)) . ";\n    }\n";
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
    class class_87373bac58cc91a097cde8b6f75577026c5bdf85 extends base_template_20e5750a0f2104effacdf0b83f627f8af5fd0276
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
     *  Template class generated from Alpha.tpl
     */
    class class_63a832d9e94dc14f656368039d30aa1f8d6813b9 extends base_template_20e5750a0f2104effacdf0b83f627f8af5fd0276
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            echo $self->result . " = ctype_alpha(" . ($input) . ");\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from AnyOf.tpl
     */
    class class_f4f0cb85093b6835d6eb2a544e6065572b50cab6 extends base_template_20e5750a0f2104effacdf0b83f627f8af5fd0276
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
                echo "    " . ($rule->toCode($input, $self)) . "\n    if (" . ($rule->result) . ") {\n        " . ($self->result) . " = true;\n        goto exit_" . (sha1($self->result)) . ";\n    }\n";
            }
            echo "exit_" . (sha1($self->result)) . ":\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from Integer.tpl
     */
    class class_c76695457189d83cc740cf9405c0d1c6d8d3e786 extends base_template_20e5750a0f2104effacdf0b83f627f8af5fd0276
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            echo $self->result . " = is_numeric(" . ($input) . ") && (int)" . ($input) . " == " . ($input) . ";\n\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from Date.tpl
     */
    class class_eeac73f6af19611258f68e085a761dea60b94970 extends base_template_20e5750a0f2104effacdf0b83f627f8af5fd0276
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            echo "if (" . ($input) . " instanceof \\DateTime) {\n    " . ($self->result) . " = true;\n} elseif (!is_string(" . ($input) . ")) {\n    " . ($self->result) . " = false;\n} else {\n";
            if (empty($args[0])) {
                echo "        " . ($self->result) . " = false !== strtotime(" . ($input) . ");\n";
            }
            else {
                echo "        \$dateFromFormat = \\DateTime::createFromFormat(";
                var_export($args[0]);
                echo ", " . ($input) . ");\n        " . ($self->result) . " = \$dateFromFormat\n                && " . ($input) . " === date(";
                var_export($args[0]);
                echo ", \$dateFromFormat->getTimestamp());\n";
            }
            echo "}\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from Between.tpl
     */
    class class_9f088ed2c64f7697e44a25fcb3616547625c8713 extends base_template_20e5750a0f2104effacdf0b83f627f8af5fd0276
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            echo $self->result . " = " . ($input) . " >= " . ($args[0]) . " && " . ($input) . " <= " . ($args[1]) . ";\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from When.tpl
     */
    class class_3402ae266a134bd7679147b3a881ffd937d42fd7 extends base_template_20e5750a0f2104effacdf0b83f627f8af5fd0276
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            echo $args[0]->toCode($input, $self) . "\n\nif (" . ($args[0]->result) . ") {\n    " . ($args[1]->toCode($input, $self)) . "\n    " . ($self->result) . " = " . ($args[1]->result) . ";\n} else {\n    " . ($args[2]->toCode($input, $self)) . "\n    " . ($self->result) . " = " . ($args[2]->result) . ";\n}\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from NotEmpty.tpl
     */
    class class_b54ce34cf20d540ab7064acb34433250ac81a323 extends base_template_20e5750a0f2104effacdf0b83f627f8af5fd0276
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            echo $self->result . " = !empty(" . ($input) . ");\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from Array.tpl
     */
    class class_e78c4f877ad52a7df58643507b799d92468cb63f extends base_template_20e5750a0f2104effacdf0b83f627f8af5fd0276
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            echo $self->result . " = is_array(" . ($input) . ") || (" . ($input) . " instanceof \\ArrayAccess\n    && " . ($input) . " instanceof \\Traversable\n    && " . ($input) . " instanceof \\Countable);\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from Body.tpl
     */
    class class_98919c47cacf71f4af08a8b2c075ad7c19e2b5b1 extends base_template_20e5750a0f2104effacdf0b83f627f8af5fd0276
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
                echo "        case ";
                var_export($name);
                echo ":\n            \$valid = " . ($func) . "(\$input);\n            break;\n";
            }
            echo "        default:\n            throw new \\Exception(\"Cannot find validator for {\$rule}\");\n    }\n    return \$valid;\n\n}\n\n";
            if (count($classes) > 0) {
                echo "function get_object_properties(\$object)\n{\n    \$class = strtolower(get_class(\$object));\n    \$data  = [];\n";
                foreach($classes as $name => $props) {
                    echo "    switch (\$class) {\n    case ";
                    var_export(strtolower($name));
                    echo ":\n";
                    foreach($props as $name => $is_public) {
                        if ($is_public) {
                            echo "                \$data[";
                            var_export($name);
                            echo "] = \$object->" . ($name) . ";\n";
                        }
                        else {
                            echo "                \$property = new \\ReflectionProperty(\$object, ";
                            var_export($name);
                            echo ");\n                \$property->setAccessible(true);\n                \$data[";
                            var_export($name);
                            echo "] = \$property->getValue(\$object);\n";
                        }
                    }
                    echo "        break;\n";
                }
                echo "    default:\n        throw new \\Exception(\"Cannot find a validations for {\$class} object\");\n    }\n    return \$data;\n}\n";
            }

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from NoWhitespace.tpl
     */
    class class_caea816a5db6e6c3998cf366128c6cf4cbec0c18 extends base_template_20e5750a0f2104effacdf0b83f627f8af5fd0276
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
     *  Template class generated from Bool.tpl
     */
    class class_ceb2e9f7134390fa5010bc47de63df845c4b22d4 extends base_template_20e5750a0f2104effacdf0b83f627f8af5fd0276
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            echo $self->result . " = is_bool(" . ($input) . ");\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from Email.tpl
     */
    class class_6a3c3199ac12f05b87a72f489af82723d8e98693 extends base_template_20e5750a0f2104effacdf0b83f627f8af5fd0276
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

    /** 
     *  Template class generated from MaxLength.tpl
     */
    class class_61bf62a27e7d3f5195a3a9d8f23898a40ae479a4 extends base_template_20e5750a0f2104effacdf0b83f627f8af5fd0276
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            echo $self->result . " = strlen(" . ($input) . ") <= " . ($args[0]) . ";\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from Writable.tpl
     */
    class class_ff0ee333d7819e4c6a8a7a9cc504850a43d79e96 extends base_template_20e5750a0f2104effacdf0b83f627f8af5fd0276
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            echo "if (" . ($input) . " instanceof \\SplFileInfo) {\n    " . ($self->result) . " = " . ($input) . "->isWritable();\n} else {\n    " . ($self->result) . " = (is_string(" . ($input) . ") && is_writable(" . ($input) . "));\n}\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

    /** 
     *  Template class generated from Xdigit.tpl
     */
    class class_d04f38951493cdac32878e31062404988e3aebe3 extends base_template_20e5750a0f2104effacdf0b83f627f8af5fd0276
    {

        public function render(Array $vars = array(), $return = false)
        {
            $this->context = $vars;

            extract($vars);
            if ($return) {
                ob_start();
            }
            echo $self->result . " = ctype_xdigit(" . ($input) . ");\n";

            if ($return) {
                return ob_get_clean();
            }

        }
    }

}

namespace crodas\Validator {

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
                'minlength.tpl' => 'class_f383bca0734f6fcb6b4d791ef8997c23e2b34e14',
                'minlength' => 'class_f383bca0734f6fcb6b4d791ef8997c23e2b34e14',
                'not.tpl' => 'class_24c861bd74b2967c8b4b91272f2165119e46e335',
                'not' => 'class_24c861bd74b2967c8b4b91272f2165119e46e335',
                'alnum.tpl' => 'class_fda5329c4d3473f86f472bff037149dac04a7069',
                'alnum' => 'class_fda5329c4d3473f86f472bff037149dac04a7069',
                'charset.tpl' => 'class_889c8c99df4438b8d719d56e98239001ce98b33d',
                'charset' => 'class_889c8c99df4438b8d719d56e98239001ce98b33d',
                'creditcard.tpl' => 'class_54c1f1fb9a99693048e9f640447249f3c659115e',
                'creditcard' => 'class_54c1f1fb9a99693048e9f640447249f3c659115e',
                'positive.tpl' => 'class_a02f0cae0d7ce37d38d758f9164c2cdaad435964',
                'positive' => 'class_a02f0cae0d7ce37d38d758f9164c2cdaad435964',
                'allof.tpl' => 'class_a11236d6e933f6694cf823c635f715839924f36a',
                'allof' => 'class_a11236d6e933f6694cf823c635f715839924f36a',
                'length.tpl' => 'class_87373bac58cc91a097cde8b6f75577026c5bdf85',
                'length' => 'class_87373bac58cc91a097cde8b6f75577026c5bdf85',
                'alpha.tpl' => 'class_63a832d9e94dc14f656368039d30aa1f8d6813b9',
                'alpha' => 'class_63a832d9e94dc14f656368039d30aa1f8d6813b9',
                'anyof.tpl' => 'class_f4f0cb85093b6835d6eb2a544e6065572b50cab6',
                'anyof' => 'class_f4f0cb85093b6835d6eb2a544e6065572b50cab6',
                'integer.tpl' => 'class_c76695457189d83cc740cf9405c0d1c6d8d3e786',
                'integer' => 'class_c76695457189d83cc740cf9405c0d1c6d8d3e786',
                'date.tpl' => 'class_eeac73f6af19611258f68e085a761dea60b94970',
                'date' => 'class_eeac73f6af19611258f68e085a761dea60b94970',
                'between.tpl' => 'class_9f088ed2c64f7697e44a25fcb3616547625c8713',
                'between' => 'class_9f088ed2c64f7697e44a25fcb3616547625c8713',
                'when.tpl' => 'class_3402ae266a134bd7679147b3a881ffd937d42fd7',
                'when' => 'class_3402ae266a134bd7679147b3a881ffd937d42fd7',
                'notempty.tpl' => 'class_b54ce34cf20d540ab7064acb34433250ac81a323',
                'notempty' => 'class_b54ce34cf20d540ab7064acb34433250ac81a323',
                'array.tpl' => 'class_e78c4f877ad52a7df58643507b799d92468cb63f',
                'array' => 'class_e78c4f877ad52a7df58643507b799d92468cb63f',
                'body.tpl' => 'class_98919c47cacf71f4af08a8b2c075ad7c19e2b5b1',
                'body' => 'class_98919c47cacf71f4af08a8b2c075ad7c19e2b5b1',
                'nowhitespace.tpl' => 'class_caea816a5db6e6c3998cf366128c6cf4cbec0c18',
                'nowhitespace' => 'class_caea816a5db6e6c3998cf366128c6cf4cbec0c18',
                'bool.tpl' => 'class_ceb2e9f7134390fa5010bc47de63df845c4b22d4',
                'bool' => 'class_ceb2e9f7134390fa5010bc47de63df845c4b22d4',
                'email.tpl' => 'class_6a3c3199ac12f05b87a72f489af82723d8e98693',
                'email' => 'class_6a3c3199ac12f05b87a72f489af82723d8e98693',
                'maxlength.tpl' => 'class_61bf62a27e7d3f5195a3a9d8f23898a40ae479a4',
                'maxlength' => 'class_61bf62a27e7d3f5195a3a9d8f23898a40ae479a4',
                'writable.tpl' => 'class_ff0ee333d7819e4c6a8a7a9cc504850a43d79e96',
                'writable' => 'class_ff0ee333d7819e4c6a8a7a9cc504850a43d79e96',
                'xdigit.tpl' => 'class_d04f38951493cdac32878e31062404988e3aebe3',
                'xdigit' => 'class_d04f38951493cdac32878e31062404988e3aebe3',
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

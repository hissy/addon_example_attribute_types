# Example Attribute Types

An example package to adding custom attribute types for concrete5 version 8

**concrete5 ver8 is still beta. Please use this package at your own risk.**

## Install

```
$ cd ./package
$ git clone git@github.com:hissy/addon_example_attribute_types.git example_attribute_types
$ cd ../
$ ./concrete/bin/concrete5 c5:package-install example_attribute_types
```

## Installed Attribute Types

### Email with Confirmation

| handle | email_confirm |
| ---- | ---- |
| Controller | \Concrete\Package\ExampleAttributeTypes\Attribute\EmailConfirm\Controller |
| Type | \Concrete\Core\Entity\Attribute\Key\Type\TextType |
| Value | \Concrete\Package\ExampleAttributeTypes\Entity\Attribute\Value\Value\ConfirmTextValue |

![email_confirm](https://github.com/hissy/addon_example_attribute_types/blob/master/screenshot1.png?raw=true)

### I Agree to Terms

| handle | tos_accepted |
| ---- | ---- |
| Controller | \Concrete\Package\ExampleAttributeTypes\Attribute\TosAccepted\Controller |
| Type | \Concrete\Package\ExampleAttributeTypes\Entity\Attribute\Key\Type\TosAcceptedType |
| Value | \Concrete\Core\Entity\Attribute\Value\Value\BooleanValue |

![tos_accepted](https://github.com/hissy/addon_example_attribute_types/blob/master/screenshot2.png?raw=true)


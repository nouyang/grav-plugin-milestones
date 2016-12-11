# Milestones Plugin

**This README.md file should be modified to describe the features, installation, configuration, and general usage of this plugin.**

The **Milestones** Plugin is for [Grav CMS](http://github.com/getgrav/grav). milestones

## Installation

Installing the Milestones plugin can be done in one of two ways. The GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

### GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's terminal (also called the command line).  From the root of your Grav install type:

    bin/gpm install milestones

This will install the Milestones plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/milestones`.

### Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `milestones`. You can find these files on [GitHub](https://github.com/nouyang/grav-plugin-milestones) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/milestones
	
> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) and the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) to operate.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/milestones/milestones.yaml` to `user/config/plugins/milestones.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true
```

## Usage

Create a form and in the process section, include milestones.

`/var/www/html/grav/user/pages/08.form/form.md`

	---
	title: Milestones
	slug: milestones


	form:
	    name: milestones

	    fields:
		- name: name
		  label: Name
		  placeholder: Enter your name
		  autofocus: on
		  autocomplete: on
		  type: text
		  validate:
		    required: true


		- name: milestones
		  label: Milestones 
		  placeholder: Enter your milestones 
		  type: textarea
		  rows: 15
		  validate:
		    required: true

	    buttons:
		- type: submit
		  value: Submit
		- type: reset
		  value: Reset

	    process:
		- message: Thank you for the milestones!
		- display: thankyou
		- milestones:

	---

	# Milestones 

	Whoo milestones!


Make sure to also create a "thankyou" subdirectory if you want to display a page with the data.

/var/www/html/dev/user/pages/08.form/thankyou $ vi formdata.md

	---
	title: Thank you
	cache_enable: false
	process:
	    twig: true
	---

	## Thanks for submitting your milestones!

If your theme doesn't have it, make sure to create a formdata.html.twig

/var/www/html/dev/user/themes/gravstrap-theme/templates $ vi formdata.html.twig

	{% extends 'partials/base.html.twig' %}

	{% block content %}

	    {{ content }}

	    <div class="alert">{{ form.message }}</div>
	    <p>Here is the summary of what you wrote to us:</p>

	    {% include "forms/data.html.twig" %}

	{% endblock %}


To make your form look pretty, you can also add this file:

/var/www/html/dev/user/themes/gravstrap-theme/css $ vi custom.css

	fieldset {
	  border: 1px solid #ddd; }

	input, textarea {
	    transition: border-color;
	    border-radius: .1875rem;
	    margin-bottom: .85rem;
	    padding: .425rem .425rem;
	    width: 100%;
	    border: 1px solid #ddd;
	}


## Credits

Thanks to the cat gifs on the internet that kept me going.

## To Do

- [ ] Add in requirements (form plugin)
- [ ] Display milestones somewhere


/** @jsx React.DOM */

var STATES = [
  'AL', 'AK', 'AS', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC', 'FL', 'GA', 'HI',
  'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD', 'MA', 'MI', 'MN', 'MS',
  'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR',
  'PA', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY'
]

var Example = React.createClass({displayName: "Example",
    getInitialState: function() {
        return {
            email: true,
            question: true,
            submitted: null
        }
    },
    render: function() {
        var submitted
        if (this.state.submitted !== null) {
            submitted = React.createElement("div", {className: "alert alert-success"},
                React.createElement("p", null, "ContactForm data:"),
                React.createElement("pre", null, React.createElement("code", null, JSON.stringify(this.state.submitted, null, '  ')))
            )
        }

        return React.createElement("div", null,
            React.createElement("div", {className: "panel panel-default"},
                React.createElement("div", {className: "panel-heading clearfix"},
                    React.createElement("h2",{className: 'contact'},"Interested? Please sign up.")
                ),
                React.createElement("div", {className: "panel-body"},
                    React.createElement(ContactForm, {ref: "contactForm",
                            email: this.state.email,
                            question: this.state.question,
                            company: this.props.company}
                    )
                ),
                React.createElement("div", {className: "panel-footer"},
                    /*React.createElement("div", {className: ""},
                        React.createElement("label", {className: "button verysmall"},
                            React.createElement("input", {type: "checkbox", ref:"contactMe", id: "contactMe"}), "Keep me up to date."
                        ),
                        React.createElement("label", {className: "button verysmall"},
                            React.createElement("input", {type: "checkbox", ref: "careRec", id: "careRec"}), "I would be the care recipient"
                        )
                    ),*/
                    React.createElement("button", {type: "button", className:"button center", onClick: this.handleSubmit}, "Submit")
                )
            ),
            submitted
        )
    },
    handleChange: function(field, e) {
        var nextState = {}
        nextState[field] = e.target.checked
        this.setState(nextState)
    },

    handleSubmit: function() {
        if (this.refs.contactForm.isValid()) {
            $.ajax({
                url: 'contactform.php',
                dataType: 'json',
                type: 'POST',
                data: this.refs.contactForm.getFormData(),
                success: function(data) {
                    console.log('success');
                }.bind(this),
                error: function(xhr, status, err) {
                    console.log('contactform.php', status, err.toString());
                }.bind(this)
            });
        }
    }
});

/**
 * A contact form with certain optional fields.
 */
var ContactForm = React.createClass({displayName: "ContactForm",
    getDefaultProps: function() {
        return {
            email: true
            , question: false
        }
    },

    getInitialState: function() {
        return {errors: {}}
    },

    isValid: function() {
        var fields = ['firstName', 'lastName', 'zipCode1', 'zipCode2']
        if (this.props.email) fields.push('email');
        if (this.props.question) fields.push('question');
        var errors = {};
        fields.forEach(function(field) {
            var value = trim(this.refs[field].getDOMNode().value);
            if (!value) {
                errors[field] = 'This field is required';
            }
        }.bind(this))
        this.setState({errors: errors});
        var isValid = true;
        for (var error in errors) {
            isValid = false
            break
        }
        return isValid
    },

    getFormData: function() {
        var data = {
            firstName: this.refs.firstName.getDOMNode().value,
            lastName: this.refs.lastName.getDOMNode().value,
            zipCode1: this.refs.zipCode1.getDOMNode().value,
            zipCode2: this.refs.zipCode2.getDOMNode().value,
            careRec: this.refs.careRec.getDOMNode().checked,
            contactMe: this.refs.contactMe.getDOMNode().checked
        }
        if (this.props.email) data.email = this.refs.email.getDOMNode().value
        if (this.props.question) data.question = this.refs.question.getDOMNode().value
        return data;
    },

    render: function() {
        return React.createElement("div", {className: "form-horizontal"},
            this.renderTextInput('firstName', 'First name'),
            this.renderTextInput('lastName', 'Last name'),
            this.props.email && this.renderTextInput('email', 'Email'),
            this.renderTextInput('zipCode1', 'Zip code'),
            this.props.question && this.renderTextarea('question', 'Question/Comment'),
            this.renderSpace('blankSpace'),
            this.renderCheckInput('careRec', 'I would be the care recipient', false),
            this.renderCheckInput('contactMe', 'Keep me up to date', true),
            this.renderSpace('blankSpace')
        )
    },

    renderCheckInput: function(id, label) {
        return this.renderCheckField(id, label,
            React.createElement("H2", {className: "button verysmall"},
                React.createElement("input", {type: "checkbox", ref:id, id: id})
            )
        )
    },

    renderTextInput: function(id, label) {
        return this.renderField(id, label,
            React.createElement("input", {type: "text", className: "button form", id: id, ref: id})
        )
    },

    renderTextarea: function(id, label) {
        return this.renderField(id, label,
            React.createElement("textarea", {className: "button form", id: id, ref: id})
        )
    },
    renderSelect: function(id, label, values) {
        var options = values.map(function(value) {
            return React.createElement("option", {value: value}, value)
        })
        return this.renderField(id, label,
            React.createElement("select", {className: "button verysmall", id: id, ref: id},
                options
            )
        )
    },
    renderField: function(id, label, field) {
        return React.createElement("div", {className: $c('form-group', {'has-error': id in this.state.errors})},
            React.createElement('h4', {htmlFor: id, className: "col-sm-4 control-label"}, label),
            React.createElement("div", {className: ""},
                field
            )
        )
    },

    renderCheckField: function(id, label, checked) {
        return React.createElement("h4", {className: "button verysmall"},
            React.createElement("input", {type: "checkbox",ref:id, id: id}), label
        )
    },

    renderSpace: function(id, label) {
        return React.createElement("div",{className:"wrapper small"});
    }
});

React.render(React.createElement(Example), document.getElementById('contactFormContent'));

// Utils

var trim = function() {
  var TRIM_RE = /^\s+|\s+$/g
  return function trim(string) {
    return string.replace(TRIM_RE, '')
  }
}()

function $c(staticClassName, conditionalClassNames) {
  var classNames = []
  if (typeof conditionalClassNames == 'undefined') {
    conditionalClassNames = staticClassName
  }
  else {
    classNames.push(staticClassName)
  }
  for (var className in conditionalClassNames) {
    if (!!conditionalClassNames[className]) {
      classNames.push(className)
    }
  }
  return classNames.join(' ')
}
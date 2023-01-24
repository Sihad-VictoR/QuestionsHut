// /**
// Backbone Javascript file to handle User Question view with Question and Answers.

// Creator: M.F.M.Sihad
// */
var base_url =
	window.location.origin + "/" + window.location.pathname.split("/")[1] + "/";

//initializing the user model
var UserModel = Backbone.Model.extend({
	defaults: {
		userId: "",
		userName: "",
		userAbout: "",
		userEmailVerified: "",
	},
});
//initializing the user model view to interact with UI elements
//Modal to edit,delete and view User details
var UserModalView = Backbone.View.extend({
	id: "base-modal",
	className: "modal fade bd-example-modal-lg",
	template: _.template($("#modal-template").html()),

	events: {
		hidden: "teardown",
		"click a#reset": "reset",
		"click a#delete": "delete",
		"click button#save": "save",
	},

	initialize: function () {
		_.bindAll(this, "show", "teardown", "render", "renderView");
		this.render();
	},

	show: function () {
		this.$el.modal("show");
	},

	teardown: function () {
		this.$el.data("modal", null);
		this.remove();
	},

	render: function () {
		console.log(this.model);
		this.renderView(this.template);
		return this;
	},

	renderView: function (template) {
		this.$el.html(template(this.model.attributes));
		this.$el.modal({ show: false }); // not showing modal on instantiation
	},
	reset: function (event) { // reset the password
		event.preventDefault();
		var userEmail = this.model.get("userEmail");
		that = this;
		$.ajax({
			method: "POST",
			url: base_url + "index.php/AuthController/checkMail",
			dataType: "JSON",
			data: {
				userEmail: userEmail,
			},
			success: function (data) {
				console.log(data);
				if (data.status) {
					alert("Account Found : Email sent!");
				} else {
					alert("No Account Found!");
				}
				that.$el.modal("hide");
			},
		});
	},
	delete: function () { //deleting an user
		let text = "Are you sure you want to delete?";
		if (confirm(text) == true) {
			$.ajax({
				type: "POST",
				url: base_url + "index.php/AuthController/deleteUser",
				success: function () {
					window.location.href = base_url + "index.php"; //redirecting to main page after deletion
				},
			});
		}
	},
	save: function (event) { //saving an user after edit/update
		event.preventDefault();
		$.ajax({
			type: "POST",
			url: base_url + "index.php/AuthController/editUser",
			dataType: "json",
			data: {
				userName: $("#uName").val(),
				userAbout: $("#uAbout").val(),
			},
			success: function (data) {
				if (data) {
					alert("Changes Saved");
					window.location.href = base_url + "index.php/profile";
				}
			},
		});
	},
});
//creating a new user
var userModel = new UserModel();
//setting up values
function createModal(user) {
	// console.log(user);
	userModel.set({
		userEmail: user["userEmail"],
		userName: user["userName"],
		userAbout: user["userAbout"],
	});
	if (parseInt(user["userEmailVerified"])) {
		userModel.set({
			userEmailVerified: `Your account is verified.`,
		});
	} else {
		userModel.set({
			userEmailVerified: `Your account is not verified`,
		});
	}

	modalView = new UserModalView({ model: userModel });
	modalView.show();
}

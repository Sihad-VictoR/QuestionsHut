// /**
// Backbone Javascript file to handle question answer view.

// Creator: M.F.M.Sihad
// */
"use strict";
//base url to get base url without php
var base_url =
	window.location.origin + "/" + window.location.pathname.split("/")[1] + "/";

var questionId = window.location.pathname.split("/").pop();

//initialize answer Model
var AnswerModel = Backbone.Model.extend({
	defaults: {
		userId: "",
		questionId: "",
		answerVoteCount: "",
		answerContent: "",
	},
});
//initiliaze answer collection with url to fetch
var AnswerCollection = Backbone.Collection.extend({
	model: AnswerModel,
	url: base_url + "index.php/HomeController/getAnswers",
});

//creating an answer collection
var newAnswerCollection = new AnswerCollection();

//initializing  answer Backbon view. to interact with UI elements
var AnswersView = Backbone.View.extend({
	el: "#question",
	collection: newAnswerCollection,
	template: _.template($("#answer-template").html()),

	initialize: function () {
		console.log("initializing view");
		//Listening to change for action triggers
		this.listenTo(this.model, "change", this.render);
		this.listenTo(this.collection, "add", this.render);
		// this.listenTo(this.collection, "change", this.render);
		this.render();
	},
	events: {
		"click button#submitForm": "addAnswer",
		"click button.upvote": "upvote",
		"click button.downvote": "downvote",
	},

	render: function () {
		$("#addAnswer").html("");
		this.addAll();
		return this;
	},
	addAll: function () {
		this.collection.each(this.addOne, this);
	},
	addOne: function (model) {
		//adding id to each model in collection
		model.set("id", parseInt(model.get("answerId")));
		$("#addAnswer").append(this.template(model.attributes));
	},
	addAnswer: function (e) {
		//adding a new answer
		e.preventDefault();
		var that = this;
		$.ajax({
			type: "POST",
			url: base_url + "index.php/HomeController/addAnswer",
			dataType: "json",
			data: {
				answerContent: $("textarea#answerArea").val(),
				questionId: questionId,
			},
			success: function (data) {
				console.log(data);
				if (data === null) {
					//toggliing Modal
					$("#ModalExample").modal("toggle");
				} else {
					$("textarea#answerArea").val("");
					that.collection.add(data);
				}
			},
		});
	},
	upvote: function (event) {
		//editing an answer using votes.
		var that = this;
		var id = $(event.currentTarget).attr("id");
		//getting collection item using id
		var searchItem = newAnswerCollection.get(parseInt(id));
		console.log(searchItem);
		$.ajax({
			type: "POST",
			url: base_url + "index.php/HomeController/voteAnswer",
			dataType: "json",
			data: {
				answerId: id,
				voteValue: 1,
			},
			success: function (data) {
				if (data === null) {
					//toggliing Modal
					$("#ModalExample").modal("toggle");
					return;
				}
				if (data.status == true) {
					searchItem.set("answerVoteCount", data.answer[0].answerVoteCount);
					searchItem.save();
					that.render();
				} else {
					alert("You have already voted as Same");
				}
			},
		});
	},
	downvote: function (event) {
		//editing an answer using votes.
		var that = this;
		var id = $(event.currentTarget).attr("id");
		var searchItem = newAnswerCollection.get(parseInt(id));
		console.log(searchItem);
		$.ajax({
			type: "POST",
			url: base_url + "index.php/HomeController/voteAnswer",
			dataType: "json",
			data: {
				answerId: id,
				voteValue: 0,
			},
			success: function (data) {
				if (data === null) {
					//toggliing Modal
					$("#ModalExample").modal("toggle");
					return;
				}
				if (data.status == true) {
					console.log(searchItem);
					searchItem.set("answerVoteCount", data.answer[0].answerVoteCount);
					searchItem.save();
					that.render();
				} else {
					alert("You have already voted as Same");
				}
			},
		});
	},
});
//fetching answers for the questions from backend.
function fetchAnswers(questionId) {
	newAnswerCollection.fetch({
		type: "POST",
		dataType: "json",
		data: {
			questionId: questionId,
		},
		success: function (data) {
			var app = new AnswersView();
		},
	});
}

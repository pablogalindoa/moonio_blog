import React, { useState } from "react";
import axios from "axios";

const BlogForm = ({ blog = {}, onSubmit }) => {
	const [title, setTitle] = useState(blog.title || "");
	const [content, setContent] = useState(blog.content || "");
	const [image, setImage] = useState(null);

	const handleImageChange = (e) => {
		setImage(e.target.files[0]);
	};

	const handleSubmit = async (e) => {
		e.preventDefault();

		const formData = new FormData();
		formData.append("title", title);
		formData.append("content", content);
		if (image) {
			formData.append("image", image);
		}

		try {
			const response = await fetch(
				"http://localhost/YOUR_CI_PROJECT/blog/store",
				{
					method: "POST",
					body: formData,
				}
			);

			if (response.ok) {
				alert("Blog post created successfully!");
				window.location.href = "/"; // Redirect to home after success
			} else {
				alert("Error creating blog post.");
			}
		} catch (error) {
			console.error("Error submitting form:", error);
			alert("Something went wrong.");
		}
	};

	return (
		<form onSubmit={handleSubmit} className="space-y-4">
			<div>
				<label
					htmlFor="title"
					className="block text-lg font-medium text-gray-700"
				>
					Title
				</label>
				<input
					type="text"
					id="title"
					value={title}
					onChange={(e) => setTitle(e.target.value)}
					className="w-full p-2 border border-gray-300 rounded"
				/>
			</div>

			<div>
				<label
					htmlFor="content"
					className="block text-lg font-medium text-gray-700"
				>
					Content
				</label>
				<textarea
					id="content"
					value={content}
					onChange={(e) => setContent(e.target.value)}
					className="w-full p-2 border border-gray-300 rounded"
				></textarea>
			</div>

			<div>
				<label
					htmlFor="image"
					className="block text-lg font-medium text-gray-700"
				>
					Upload Image
				</label>
				<input
					type="file"
					id="image"
					onChange={handleImageChange}
					className="w-full p-2 border border-gray-300 rounded"
				/>
			</div>

			<button
				type="submit"
				className="px-4 py-2 bg-blue-600 text-white rounded"
			>
				Submit
			</button>
		</form>
	);
};

export default BlogForm;

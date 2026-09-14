import React, { useState } from "react";

const Login = () => {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [message, setMessage] = useState("");

  const handleLogin = async () => {
    try {
      const response = await fetch("http://localhost/index.php/employee/loginAuth", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ email: email, password: password }),
      });
  
      // Check if the response is OK (status 200)
      if (!response.ok) {
        throw new Error(`HTTP Error! Status: ${response.status}`);
      }
  
      // Get the raw text response
      const text = await response.text();
      console.log("Raw Response:", text);
  
      // Try parsing JSON
      let data;
      try {
        data = JSON.parse(text);
      } catch (jsonError) {
        throw new Error("Invalid JSON response from server.");
      }
  
      console.log("Parsed Data:", data);
  
      if (data.status === "error") {
        console.log("Login Successful:", data.message);
        alert("Login Successfull!");
        // Redirect or store user info
      } else {
        console.error("Login failed:", data.message);
        alert("Invalid credentials, please try again.");
      }
    } catch (error) {
      console.error("Fetch error:", error);
      alert("An error occurred while logging in. Please try again later.");
    }
  };

  return (
    <div style={{ textAlign: "center", padding: "50px" }}>
      <h2>Login</h2>
      {message && <p style={{ color: "red" }}>{message}</p>}
      <form onSubmit={handleLogin}>
        <div>
          <input
            type="email"
            placeholder="Email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
          />
        </div>
        <div>
          <input
            type="password"
            placeholder="Password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
          />
        </div>
        <button type="submit">Login</button>
      </form>
    </div>
  );
};

export default Login;

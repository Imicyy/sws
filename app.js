require("dotenv").config();

const path = require("path");
const express = require("express");
const session = require("express-session");
const http = require("http");
const socketIo = require("socket.io");

const routes = require("./routes/routes");
const connection = require("./model/database");
const { getConnection } = require("./model/databasesql");

const app = express();
const server = http.createServer(app);
const io = socketIo(server, {
    cors: {
        origin: "*"
    }
});

const PORT = process.env.PORT || 3000;

// ======================
// VIEW ENGINE
// ======================
app.set("view engine", "ejs");
app.set("views", path.join(__dirname, "default"));

// ======================
// MIDDLEWARE
// ======================
app.use(express.static(path.join(__dirname, "files")));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// ======================
// SESSION (FIXED)
// ======================
app.use(session({
    secret: process.env.SESSION_SECRET || "fallback_secret_key",
    resave: false,
    saveUninitialized: false,
    cookie: {
        secure: false, // change to true if HTTPS
        httpOnly: true
    }
}));

// ======================
// DATABASE CONNECTIONS
// ======================

// MongoDB
connection()
    .then(() => console.log("MongoDB Connected"))
    .catch(err => {
        console.error("MongoDB Connection Failed:", err.message);
        process.exit(1); // stop container if DB fails
    });

// MySQL (non-blocking)
(async () => {
    try {
        const conn = await getConnection();
        console.log("MySQL Connected");
        conn.release();
    } catch (err) {
        console.error("MySQL Connection Failed:", err.message);
    }
})();

// ======================
// SOCKET.IO
// ======================
io.on("connection", (socket) => {
    console.log("User connected:", socket.id);

    socket.on("join-room", (room) => {
        if (["staff", "youth"].includes(room)) {
            socket.join(room);
            console.log(`User ${socket.id} joined ${room}`);
        }
    });

    socket.on("disconnect", () => {
        console.log("User disconnected:", socket.id);
    });
});

// Make io available in routes
app.use((req, res, next) => {
    req.io = io;
    next();
});

// ======================
// ROUTES
// ======================
app.use("/", routes);

// ======================
// START SERVER
// ======================
server.listen(PORT, "0.0.0.0", () => {
    console.log(`Server running on port ${PORT}`);
});

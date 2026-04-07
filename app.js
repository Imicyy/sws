require("dotenv").config();
const path = require("path");
const express = require("express"); 
const bodyParser = require("body-parser");
const routes = require("./routes/routes");
const connection = require("./model/database");
const { getConnection } = require("./model/databasesql");
const session = require('express-session');
const http = require('http');
const socketIo = require('socket.io');

const app = express();
const server = http.createServer(app);
const io = socketIo(server);
const PORT = process.env.PORT || 3000;

// Set EJS as the templating engine
app.set("view engine", "ejs");

app.set("views", path.join(__dirname, "default"));

// Middleware to serve static files
app.use(express.static(path.join(__dirname, "files")));

// Middleware to parse request body
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

app.use(session({
    secret: process.env.SESSION_SECRET, // Change this to a strong, random string
    resave: false,
    saveUninitialized: true,
    cookie: { secure: false } // Set to true if using HTTPS
}));

// MongoDB connection
connection();

// Test MySQL (mysql2) connection on startup without crashing the app
(async () => {
    try {
        const conn = await getConnection();
        console.log("MySQL (mysql2) Connected Successfully!");
        conn.release();
    } catch (err) {
        console.error("MySQL (mysql2) Connection Failed:", err.message);
    }
})();

// Socket.io connection handling
io.on('connection', (socket) => {
    console.log('New user connected:', socket.id);

    // Handle user joining a room
    socket.on('join-room', (room) => {
        if (room === 'staff' || room === 'youth') {
            socket.join(room);
            console.log(`User ${socket.id} joined ${room} room`);
        }
    });

    // Handle disconnect
    socket.on('disconnect', () => {
        console.log('User disconnected:', socket.id);
    });
});

// Make io accessible to routes
app.use((req, res, next) => {
    req.io = io;
    next();
});

// Import routes
app.use("/", routes);

// Start server
// app.listen(PORT, () => {
//     console.log(`Server is running on http://localhost:${PORT}`);
// });

//hosted
server.listen(PORT, '0.0.0.0',() => {
    console.log(`Server is running`);
});

//to do
// edit logs on OSCA and PWD, Analytics, MAPS, Barangay

// edit logs on pwd still not on database, might be error on date same as error on 
//all of youth

require("dotenv").config();
const path = require("path");
const express = require("express"); 
const bodyParser = require("body-parser");
const session = require('express-session');
const http = require('http');
const socketIo = require('socket.io');

// --- START DEBUG LOGS ---
console.log("-----------------------------------------");
console.log("🚀 SERVER STARTING - ENVIRONMENT CHECK:");
console.log("DB_HOST:", process.env.DB_HOST || "❌ NOT SET");
console.log("DB_USER:", process.env.DB_USER || "❌ NOT SET");
console.log("MYSQL_DATABASE:", process.env.MYSQL_DATABASE || "❌ NOT SET");
console.log("PORT:", process.env.PORT || "3000 (Default)");
// Check password existence without showing the actual string for security
console.log("DB_PASSWORD_PROVIDED:", process.env.DB_PASSWORD ? "✅ YES" : "❌ NO");
console.log("MYSQL_ROOT_PASSWORD_PROVIDED:", process.env.MYSQL_ROOT_PASSWORD ? "✅ YES" : "❌ NO");
console.log("-----------------------------------------");
// --- END DEBUG LOGS ---

const { getConnection } = require("./model/databasesql");
const app = express();
const server = http.createServer(app);
const io = socketIo(server);
const PORT = process.env.PORT || 3000;

app.set("view engine", "ejs");
app.set("views", path.join(__dirname, "default"));

app.use(express.static(path.join(__dirname, "files")));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

app.use(session({
    secret: process.env.SESSION_SECRET || "fallback_secret", 
    resave: false,
    saveUninitialized: true,
    cookie: { secure: false } 
}));

// Detailed Database Test logic
(async () => {
    try {
        console.log("📡 Attempting to connect to database at:", process.env.DB_HOST || "db");
        const conn = await getConnection();
        console.log("✅ MySQL Connected Successfully!");
        conn.release();
    } catch (err) {
        console.error("❌ DATABASE CONNECTION ERROR:");
        console.error("- Error Message:", err.message);
        console.error("- Error Code:", err.code);
        console.error("- Host Attempted:", err.address || "check config");
        console.error("- Port Attempted:", err.port || "3000");
    }
})();

io.on('connection', (socket) => {
    console.log('👤 Socket connected:', socket.id);
    socket.on('join-room', (room) => {
        socket.join(room);
        console.log(`🏠 User joined room: ${room}`);
    });
});

app.use((req, res, next) => {
    req.io = io;
    next();
});

const routes = require("./routes/routes");
app.use("/", routes);

server.listen(PORT, '0.0.0.0', () => {
    console.log(`🟢 App is online on port ${PORT}`);
});
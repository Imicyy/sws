require("dotenv").config();
const mysql = require("mysql2/promise");

// MySQL connection pool using mysql2
// Configure these in your .env:
// MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB

console.log("DEBUG: Password value is:", process.env.MYSQL_PASSWORD ? "FOUND" : "MISSING");
const pool = mysql.createPool({
  // No strings here! Just the environment variables.
  host: process.env.DB_HOST, 
  user: process.env.DB_USER,
  password: process.env.MYSQL_ROOT_PASSWORD,
  database: process.env.MYSQL_DATABASE,
  
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0,
  connectTimeout: 10000 
});


// Simple helpers so controllers can use:
//  const { query, getConnection } = require("../model/databasesql");
//  const [rows] = await query("SELECT ...", [params]);
const query = (...args) => pool.query(...args);
const getConnection = () => pool.getConnection();

module.exports = {
  pool,
  query,
  getConnection,
};

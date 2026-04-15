require("dotenv").config();
const mysql = require("mysql2/promise");

// MySQL connection pool using mysql2
// Configure these in your .env:
// MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB
const pool = mysql.createPool({
  host: process.env.DB_HOST || "db",
  user: process.env.DB_USER || "root",
  password: process.env.MYSQL_ROOT_PASSWORD,
  database: process.env.MYSQL_DATABASE || "ebmag",
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0,
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

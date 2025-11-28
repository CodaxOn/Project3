import express from "express";
import helmet from "helmet";
import xssClean from "xss-clean";
import cors from "cors";

import userRoutes from "./routes/user.routes.js";

const app = express();

// 🔐 Sécurité globale
app.use(helmet());           // protège headers
app.use(xssClean());         // bloque XSS
app.use(cors());             // contrôle accès cross-origin
app.use(express.json());     // body parser

// Routes API
app.use("/api/users", userRoutes);

// Route test
app.get("/", (req, res) => {
  res.json({ message: "API sécurisée opérationnelle 🚀" });
});

const PORT = 3000;
app.listen(PORT, () => console.log(`Server running on port ${PORT}`));

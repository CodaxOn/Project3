import express from "express";
import { createUser, getProfile } from "../controllers/user.controller.js";
import { auth } from "../middlewares/auth.js";
import { validate } from "../middlewares/validate.js";
import { userSchema } from "../schemas/user.schema.js";

const router = express.Router();

// Créer un utilisateur
router.post("/register", validate(userSchema), createUser);

// Récupérer son profil (sécurisé)
router.get("/profile", auth, getProfile);

export default router;

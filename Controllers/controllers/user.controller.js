
import jwt from "jsonwebtoken";

export function createUser(req, res) {
  const { username, email } = req.body;

  // ⚠️ normalement tu enregistres dans la base de données

  const token = jwt.sign(
    { username, email },
    "SECRET_KEY",
    { expiresIn: "2h" }
  );

  return res.json({
    message: "Utilisateur créé",
    token
  });
}

export function getProfile(req, res) {
  return res.json({
    message: "Profil récupéré",
    user: req.user
  });
}


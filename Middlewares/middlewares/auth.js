import jwt from "jsonwebtoken";

export function auth(req, res, next) {
  const token = req.headers.authorization;

  if (!token)
    return res.status(401).json({ error: "Token manquant" });

  try {
    const decoded = jwt.verify(token.split(" ")[1], "SECRET_KEY");
    req.user = decoded;
    next();
  } catch (err) {
    return res.status(401).json({ error: "Token invalide" });
  }
}

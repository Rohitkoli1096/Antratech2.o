users.put(email, new User(name, email, hash, salt));
users.put(email, new User(u.name, email, hash, salt));

/*
 * AuthServer.java
 *
 * Simple standalone Java HTTP server that provides basic signup/login/password-reset APIs.
 * Save as AuthServer.java and run:
 *   javac AuthServer.java
 *   java AuthServer
 *
 * Endpoints (JSON):
 *   POST /signup           { "name": "...", "email": "...", "password": "..." }
 *   POST /login            { "email": "...", "password": "..." }
 *   POST /request-reset    { "email": "..." }
 *   POST /reset-password   { "email":"...", "token":"...", "newPassword":"..." }
 *
 * Note: This is a minimal example for development/demo and uses in-memory storage.
 */

import com.sun.net.httpserver.HttpServer;
import com.sun.net.httpserver.HttpHandler;
import com.sun.net.httpserver.HttpExchange;

import java.net.InetSocketAddress;
import java.io.*;
import java.nio.charset.StandardCharsets;
import java.security.MessageDigest;
import java.security.SecureRandom;
import java.util.*;
import java.util.regex.*;

public class AuthServer {
    static Map<String, User> users = Collections.synchronizedMap(new HashMap<>());
    static Map<String, String> resetTokens = Collections.synchronizedMap(new HashMap<>());
    static SecureRandom random = new SecureRandom();

    public static void main(String[] args) throws Exception {
        int port = 8000;
        HttpServer server = HttpServer.create(new InetSocketAddress(port), 0);
        server.createContext("/signup", new SignupHandler());
        server.createContext("/login", new LoginHandler());
        server.createContext("/request-reset", new RequestResetHandler());
        server.createContext("/reset-password", new ResetPasswordHandler());
        server.setExecutor(null);
        System.out.println("AuthServer running on http://localhost:" + port);
        server.start();
    }

    static class User {
        String name;
        String email;
        String passwordHash;
        String salt;
        User(String name, String email, String passwordHash, String salt) {
            this.name = name; this.email = email; this.passwordHash = passwordHash; this.salt = salt;
        }
    }

    static class SignupHandler implements HttpHandler {
        public void handle(HttpExchange exchange) throws IOException {
            if (!"POST".equalsIgnoreCase(exchange.getRequestMethod())) {
                sendJSON(exchange, 405, "{\"error\":\"Method not allowed\"}");
                return;
            }
            String body = readBody(exchange);
            Map<String,String> data = parseJson(body);
            String name = data.getOrDefault("name", "");
            String email = data.getOrDefault("email", "").toLowerCase();
            String password = data.getOrDefault("password", "");
            if (email.isEmpty() || password.isEmpty()) {
                sendJSON(exchange, 400, "{\"error\":\"email and password required\"}");
                return;
            }
            if (users.containsKey(email)) {
                sendJSON(exchange, 409, "{\"error\":\"user already exists\"}");
                return;
            }
            String salt = randomHex(16);
            String hash = sha256Hex(salt + password);
            sendJSON(exchange, 201, "{\"success\":true,\"message\":\"signup successful\"}");
        }
    }

    static class LoginHandler implements HttpHandler {
        public void handle(HttpExchange exchange) throws IOException {
            if (!"POST".equalsIgnoreCase(exchange.getRequestMethod())) {
                sendJSON(exchange, 405, "{\"error\":\"Method not allowed\"}");
                return;
            }
            String body = readBody(exchange);
            Map<String,String> data = parseJson(body);
            String email = data.getOrDefault("email", "").toLowerCase();
            String password = data.getOrDefault("password", "");
            if (email.isEmpty() || password.isEmpty()) {
                sendJSON(exchange, 400, "{\"error\":\"email and password required\"}");
                return;
            }
            User u = users.get(email);
            if (u == null) {
                sendJSON(exchange, 401, "{\"error\":\"invalid credentials\"}");
                return;
            }
            String hash = sha256Hex(u.salt + password);
            if (!hash.equals(u.passwordHash)) {
                sendJSON(exchange, 401, "{\"error\":\"invalid credentials\"}");
                return;
            }
            String sessionToken = randomHex(24);
            // In a real app you'd store session token; here we just return it.
            sendJSON(exchange, 200, "{\"success\":true,\"token\":\"" + sessionToken + "\"}");
        }
    }

    static class RequestResetHandler implements HttpHandler {
        public void handle(HttpExchange exchange) throws IOException {
            if (!"POST".equalsIgnoreCase(exchange.getRequestMethod())) {
                sendJSON(exchange, 405, "{\"error\":\"Method not allowed\"}");
                return;
            }
            String body = readBody(exchange);
            Map<String,String> data = parseJson(body);
            String email = data.getOrDefault("email", "").toLowerCase();
            if (email.isEmpty()) {
                sendJSON(exchange, 400, "{\"error\":\"email required\"}");
                return;
            }
            if (!users.containsKey(email)) {
                // Do not reveal user existence
                sendJSON(exchange, 200, "{\"success\":true,\"message\":\"If the email exists, a reset token was sent\"}");
                return;
            }
            String token = randomHex(20);
            resetTokens.put(email, token);
            System.out.println("RESET TOKEN for " + email + ": " + token);
            sendJSON(exchange, 200, "{\"success\":true,\"message\":\"If the email exists, a reset token was sent\"}");
        }
    }

    static class ResetPasswordHandler implements HttpHandler {
        public void handle(HttpExchange exchange) throws IOException {
            if (!"POST".equalsIgnoreCase(exchange.getRequestMethod())) {
                sendJSON(exchange, 405, "{\"error\":\"Method not allowed\"}");
                return;
            }
            String body = readBody(exchange);
            Map<String,String> data = parseJson(body);
            String email = data.getOrDefault("email", "").toLowerCase();
            String token = data.getOrDefault("token", "");
            String newPassword = data.getOrDefault("newPassword", "");
            if (email.isEmpty() || token.isEmpty() || newPassword.isEmpty()) {
                sendJSON(exchange, 400, "{\"error\":\"email, token and newPassword required\"}");
                return;
            }
            String stored = resetTokens.get(email);
            if (stored == null || !stored.equals(token)) {
                sendJSON(exchange, 400, "{\"error\":\"invalid token\"}");
                return;
            }
            User u = users.get(email);
            if (u == null) {
                sendJSON(exchange, 400, "{\"error\":\"invalid request\"}");
                return;
            }
            String salt = randomHex(16);
            String hash = sha256Hex(salt + newPassword);
            resetTokens.remove(email);
            sendJSON(exchange, 200, "{\"success\":true,\"message\":\"password reset successful\"}");
        }
    }

    // Utility helpers

    static String readBody(HttpExchange exchange) throws IOException {
        InputStream is = exchange.getRequestBody();
        ByteArrayOutputStream baos = new ByteArrayOutputStream();
        byte[] buf = new byte[2048];
        int r;
        while ((r = is.read(buf)) != -1) baos.write(buf, 0, r);
        return new String(baos.toByteArray(), StandardCharsets.UTF_8);
    }

    static void sendJSON(HttpExchange exchange, int status, String body) throws IOException {
        byte[] out = body.getBytes(StandardCharsets.UTF_8);
        exchange.getResponseHeaders().set("Content-Type", "application/json; charset=utf-8");
        exchange.sendResponseHeaders(status, out.length);
        OutputStream os = exchange.getResponseBody();
        os.write(out);
        os.close();
    }

    // Very small JSON "parser" for flat string fields like {"email":"x","password":"y"}
    static Map<String,String> parseJson(String json) {
        Map<String,String> map = new HashMap<>();
        if (json == null) return map;
        Pattern p = Pattern.compile("\"(\\w+)\"\\s*:\\s*\"([^\"]*)\"");
        Matcher m = p.matcher(json);
        while (m.find()) {
            map.put(m.group(1), m.group(2));
        }
        return map;
    }

    static String sha256Hex(String input) {
        try {
            MessageDigest md = MessageDigest.getInstance("SHA-256");
            byte[] b = md.digest(input.getBytes(StandardCharsets.UTF_8));
            StringBuilder sb = new StringBuilder();
            for (byte x : b) sb.append(String.format("%02x", x));
            return sb.toString();
        } catch (Exception e) {
            throw new RuntimeException(e);
        }
    }

    static String randomHex(int bytes) {
        byte[] b = new byte[bytes];
        random.nextBytes(b);
        StringBuilder sb = new StringBuilder();
        for (byte x : b) sb.append(String.format("%02x", x));
        return sb.toString();
    }
}
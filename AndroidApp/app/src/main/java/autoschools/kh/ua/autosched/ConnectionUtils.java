package autoschools.kh.ua.autosched;

import android.content.Context;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.util.Log;

import java.io.UnsupportedEncodingException;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

public final class ConnectionUtils {
    private static final String BAD_RESPONSE = "bad_response";

    public static String GetAuthenticationString(String login, String password) {
        return "http://www.autoschools.kh.ua/index.php/mobile/login/user/" + login.trim()
                + "/password/" + md5(password.trim());
    }

    public static String GetRegistrationLink() {
        return "http://www.autoschools.kh.ua/index.php/site/registration";
    }

    public static String GetTheoryScheduleString(String login, String password) {
        return "http://www.autoschools.kh.ua/index.php/mobile/Theory/user/" + login.trim()
                + "/password/" + md5(password.trim());
    }

    public static String GetPracticeScheduleString(String login, String password) {
        return "http://www.autoschools.kh.ua/index.php/mobile/practice/user/" + login.trim()
                + "/password/" + md5(password.trim());
    }

    public static String getUserFromResponse(String s) {
        return s.equals("LoginPasswordError")
                ? BAD_RESPONSE
                : s.split(";")[1];
    }


    public static boolean isLoginValid(String login) {
        return login.length() > 3;
    }

    public static boolean isPasswordValid(String password) {
        return password.length() > 2;
    }

    private static String md5(String str) {
        byte[] hash = null;

        try {
            hash = MessageDigest.getInstance("MD5").digest(str.getBytes("UTF-8"));
        } catch (NoSuchAlgorithmException | UnsupportedEncodingException e) {
            Log.e(e.toString(), e.getMessage());
            e.printStackTrace();
        }

        try {
            StringBuilder hex = new StringBuilder((hash != null ? hash.length : 0) * 2);

            for (byte b : hash != null ? hash : new byte[0]) {
                int i = (b & 0xFF);
                if (i < 0x10)
                    hex.append('0');
                hex.append(Integer.toHexString(i));
            }
            return hex.toString();
        } catch (NullPointerException e) {
            e.printStackTrace();
            return "0";
        }
    }

    public static boolean isOnline(Context context) {
        ConnectivityManager cm = (ConnectivityManager)
                context.getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo netInfo = cm.getActiveNetworkInfo();
        return netInfo != null && netInfo.isConnectedOrConnecting();
    }
}


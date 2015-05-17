using System.Data;
using MySql.Data.MySqlClient;

namespace Autoschool
{
    public abstract class Model
    {

        public static string ConnectionString { get; set; }

        public static bool CheckConnection(string connString)
        {
            using (var conn = new MySqlConnection(connString))
            {
                try
                {
                    conn.Open();
                    if (conn.State != ConnectionState.Open) return false;
                    conn.Close();
                    return true;
                }
                catch
                {
                    return false;
                }
            }
        }
    }
}

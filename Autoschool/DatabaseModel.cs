using System;
using System.Collections.Generic;
using System.Data;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using MySql.Data.MySqlClient;

namespace Autoschool
{
    public class DatabaseModel : Model
    {
        public new static string ConnectionString
        {
            get { return "SERVER=127.0.0.1;PORT=3306;DATABASE=autoschool;Uid=root;Pwd=;"; }
        }

        public static string GetData()
        {
            if (!CheckConnection(ConnectionString))
            {
                throw new Exception("Ошибка подключения");
            }

            var result = new StringBuilder();

            var connection = new MySqlConnection(ConnectionString);

            const string command = "SELECT autoschool.* FROM autoschool;";

            var cmd = new MySqlCommand(command, connection);

            connection.Open();
            cmd.ExecuteNonQuery();
            var reader = cmd.ExecuteReader();
            cmd.CommandType = CommandType.Text;
            while (reader.Read())
            {
                result.Append(reader["name"]).Append(Environment.NewLine);
            }

            return result.ToString();
        }
    }
}

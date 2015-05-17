using System;
using System.Collections.Generic;
using System.Data;
using System.Linq;
using System.Text;
using MySql.Data.MySqlClient;

namespace Autoschool
{
    class WebsiteModel : Model
    {
        private static MySqlConnection _connection;

        public new static string ConnectionString{
            get
            {
                return "SERVER=linascaf.mysql.ukraine.com.ua;DATABASE=linascaf_auto;Uid=linascaf_auto;Pwd=wy7mpybw;";
            }
        }

        public static List<User> GetUser()
        {
            var result = new List<User>();
            var reader = GetDataReader("SELECT user.* FROM user;");
            while (reader.Read())
            {
                result.Add(new User()
                {
                    Address = reader["address"].ToString(),
                    AutoschoolId = reader["autoschool_id"].ToString(),
                    Email = reader["email"].ToString(),
                    Id = reader["id"].ToString(),
                    Login = reader["login"].ToString(),
                    Name = reader["name"].ToString(),
                    Password = reader["password"].ToString(),
                    Role = reader["role"].ToString(),
                    Telephone = reader["telephone"].ToString()
                });
            }
            _connection.Close();
            return result;
        }

        private static MySqlDataReader GetDataReader(string query)
        {
            if (!CheckConnection(ConnectionString))
            {
                throw new Exception("Ошибка подключения");
            }

            _connection = new MySqlConnection(ConnectionString);

            var sql = query;

            var cmd = new MySqlCommand(sql, _connection);

            _connection.Open();
            cmd.ExecuteNonQuery();
            var reader = cmd.ExecuteReader();
            cmd.CommandType = CommandType.Text;
            return reader;
        }

        public static List<School> GetAutoschool()
        {
            var result = new List<School>();
            var reader = GetDataReader("SELECT autoschool.* FROM autoschool;");
            while (reader.Read())
            {
                result.Add(new School()
                {
                    Contacts = reader["contacts"].ToString(),
                    Info = reader["info"].ToString(),
                    Price = reader["price"].ToString(),
                    Id = reader["id"].ToString(),
                    StudentCode = reader["studentCode"].ToString(),
                    Name = reader["name"].ToString(),
                    TeacherCode = reader["teacherCode"].ToString()
                });
            }
            _connection.Close();
            return result;
        }

        public static List<Group> GetGroup()
        {
            var result = new List<Group>();
            var reader = GetDataReader("SELECT `group`.* FROM `group`;");
            while (reader.Read())
            {
                result.Add(new Group()
                {
                    GroupId = reader["group_id"].ToString(),
                    AutoschoolId = reader["autoschool_id"].ToString(),
                    PracticeDays = reader["practice_days"].ToString(),
                    PracticeMeetpoint = reader["practice_meetpoint"].ToString(),
                    PracticeReservCount = reader["practice_reserv_count"].ToString(),
                    Name = reader["name"].ToString(),
                    PracticeStart = reader["practice_start"].ToString(),
                    PracticeTeacher = reader["practice_teacher"].ToString()
                });
            }
            _connection.Close();
            return result;
        }
    }
}

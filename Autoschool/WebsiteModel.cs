using System;
using System.Collections.Generic;
using System.Data;
using MySql.Data.MySqlClient;

namespace Autoschool
{
    class WebsiteModel : Model
    {
        private static MySqlConnection _connection;

        public new static string ConnectionString{
            get
            {
                return "SERVER=linascaf.mysql.ukraine.com.ua;DATABASE=linascaf_auto;Uid=linascaf_auto;Pwd=wy7mpybw;Charset=utf8;";
            }
        }

        public static List<User> GetUser()
        {
            var result = new List<User>();
            var reader = GetDataReader("SELECT user.* FROM user;");
            while (reader.Read())
            {
                result.Add(new User
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

        public static List<School> GetAutoschool()
        {
            var result = new List<School>();
            var reader = GetDataReader("SELECT autoschool.* FROM autoschool;");
            while (reader.Read())
            {
                result.Add(new School
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
                result.Add(new Group
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

        public static List<Practice> GetPractice()
        {
            var result = new List<Practice>();
            var reader = GetDataReader("SELECT `practice`.* FROM `practice`;");
            while (reader.Read())
            {
                result.Add(new Practice
                {
                    PracticeId = reader["practice_id"].ToString(),
                    StudentId = reader["student_id"].ToString(),
                    Lesson = reader["lesson"].ToString(),
                    Day = reader["day"].ToString(),
                });
            }
            _connection.Close();
            return result;
        }

        public static List<StudentEntry> GetStudentEntry()
        {
            var result = new List<StudentEntry>();
            var reader = GetDataReader("SELECT * FROM `student_entry`;");
            while (reader.Read())
            {
                result.Add(new StudentEntry
                {
                    EntryId = reader["entry_id"].ToString(),
                    StudentId = reader["student_id"].ToString(),
                    SchoolId = reader["school_id"].ToString(),
                    EntryTime = reader["entry_time"].ToString(),
                    AdditionalInfo = reader["additional_info"].ToString()
                });
            }
            _connection.Close();
            return result;
        }

        public static List<Theory> GetTheory()
        {
            var result = new List<Theory>();
            var reader = GetDataReader("SELECT * FROM `theory`;");
            while (reader.Read())
            {
                result.Add(new Theory
                {
                    TheoryId = reader["theory_id"].ToString(),
                    TeacherId = reader["teacher_id"].ToString(),
                    GroupId = reader["group_id"].ToString(),
                    Room = reader["room"].ToString(),
                    StartTime = reader["start_time"].ToString(),
                    EndTime = reader["end_time"].ToString()
                });
            }
            _connection.Close();
            return result;
        }

        public static List<StudentToGroup> GetStudentToGroup()
        {
            var result = new List<StudentToGroup>();
            var reader = GetDataReader("SELECT * FROM `student_to_group`;");
            while (reader.Read())
            {
                result.Add(new StudentToGroup
                {
                    StudentId = reader["student_id"].ToString(),
                    GroupId = reader["group_id"].ToString()
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
    }
}

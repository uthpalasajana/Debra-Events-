using debraApp.DAL;
using debraApp.Models;
using Microsoft.EntityFrameworkCore;
using System;
using System.Linq;

namespace debraApp
{
    public class Seed
    {
        private readonly DataContext dataContext;

        public Seed(DataContext context)
        {
            this.dataContext = context;
        }

        public void SeedDataContext()
        {
            if (!dataContext.Partners.Any())
            {
                // Add partners
                var partners = new Partner[]
                {
                    new Partner
                    {
                        Name = "Kamal Perera",
                        ContactInfo = "0776521122",
                        Address = "Colombo",
                        Email = "kamal@gmail.com",
                        Password = "123",
                        RegisteredDate = DateTime.Now
                    },
                    new Partner
                    {
                        Name = "Saman Fernando",
                        ContactInfo = "0715211122",
                        Address = "Negombo",
                        Email = "saman@gmail.com",
                        Password = "123",
                        RegisteredDate = DateTime.Now
                    }
                };
                dataContext.Partners.AddRange(partners);
                dataContext.SaveChanges();

                // Add events
                var events = new Event[]
                {
                    new Event
                    {
                        PartnerID = partners[0].PartnerID,
                        EventName = "Aluth Kalawak",
                        Description = "Starring: Wasthi, Centigrades, Infinity",
                        Date = "2024-12-15",
                        Time = "06:00",
                        Location = "Nelum Kuluna",
                        CreatedDate = DateTime.Now,
                        EventImage = "images/aluthkalawak.jpg" // Image path
                    },
                    new Event
                    {
                        PartnerID = partners[1].PartnerID,
                        EventName = "Parinamaya",
                        Description = "Starring: Chamara Weerasinghe, Yuki, Nadeemal.",
                        Date = "2024-11-25",
                        Time = "07:00",
                        Location = "Viharamahadevi Open Air Theatre",
                        CreatedDate = DateTime.Now,
                        EventImage = "images/parinamaya.png" // Image path
                    }
                };
                dataContext.Events.AddRange(events);
                dataContext.SaveChanges();

                // Add tickets
                var tickets = new Ticket[]
                {
                    new Ticket
                    {
                        EventID = events[0].EventID,
                        TicketType = "Gold",
                        Price = 5000m,
                        Quantity = 100,
                        Sold = 1 // 1 ticket sold
                    },
                    new Ticket
                    {
                        EventID = events[0].EventID,
                        TicketType = "Silver",
                        Price = 3000m,
                        Quantity = 200,
                        Sold = 0 // No tickets sold
                    },
                    new Ticket
                    {
                        EventID = events[1].EventID,
                        TicketType = "VIP",
                        Price = 10000m,
                        Quantity = 50,
                        Sold = 1 // 1 ticket sold
                    },
                    new Ticket
                    {
                        EventID = events[1].EventID,
                        TicketType = "Normal",
                        Price = 2000m,
                        Quantity = 300,
                        Sold = 0 // No tickets sold
                    }
                };
                dataContext.Tickets.AddRange(tickets);
                dataContext.SaveChanges();

                // Add customers
                var customers = new Customer[]
                {
                    new Customer
                    {
                        Name = "Mahinda Rajapaksha",
                        Email = "mahind@gmail.com",
                        PhoneNumber = "0776392210",
                        NIC = "786321221V",
                    },
                    new Customer
                    {
                        Name = "Kusal Mendis",
                        Email = "kusalmendis@gmail.com",
                        PhoneNumber = "0776821211",
                        NIC = "876341256V",
                    }
                };
                dataContext.Customers.AddRange(customers);
                dataContext.SaveChanges();

                // Add sales
                var sales = new Sale[]
                {
                    new Sale
                    {
                        TicketID = tickets[0].TicketID,
                        CustomerID = customers[0].CustomerID,
                        SaleDate = DateTime.Now,
                        TicketNumber = 1
                    },
                    new Sale
                    {
                        TicketID = tickets[2].TicketID,
                        CustomerID = customers[1].CustomerID,
                        SaleDate = DateTime.Now,
                        TicketNumber = 1
                    }
                };
                dataContext.Sales.AddRange(sales);
                dataContext.SaveChanges();

                // Add commissions
                var commissions = new Commission[]
                {
                    new Commission
                    {
                        EventID = events[0].EventID,
                        CommissionRate = 0.2m,
                        TotalSales = 5000m, // 1 Gold ticket sold at 5000m
                    },
                    new Commission
                    {
                        EventID = events[1].EventID,
                        CommissionRate = 0.1m,
                        TotalSales = 10000m, // 1 VIP ticket sold at 10000m
                    }
                };
                dataContext.Commissions.AddRange(commissions);
                dataContext.SaveChanges();

                // Add users
                var users = new User[]
                {
                    new User
                    {
                        Name = "Admin Debra",
                        Email = "admin@gmail.com",
                        Password = "123",
                        RegisteredDate = DateTime.Now
                    }
                    
                };
                dataContext.Users.AddRange(users);
                dataContext.SaveChanges();
            }
        }
    }
}
